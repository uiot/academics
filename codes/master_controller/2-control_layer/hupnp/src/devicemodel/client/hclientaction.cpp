/*
 *  Copyright (C) 2010, 2011 Tuomo Penttinen, all rights reserved.
 *
 *  Author: Tuomo Penttinen <tp@herqq.org>
 *
 *  This file is part of Herqq UPnP (HUPnP) library.
 *
 *  Herqq UPnP is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Lesser General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  Herqq UPnP is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *  GNU Lesser General Public License for more details.
 *
 *  You should have received a copy of the GNU Lesser General Public License
 *  along with Herqq UPnP. If not, see <http://www.gnu.org/licenses/>.
 */

#include "hclientaction.h"
#include "hclientaction_p.h"

#include "hclientdevice.h"
#include "hclientservice.h"
#include "hdefault_clientaction_p.h"

#include "../../general/hlogger_p.h"

#include "../../general/hupnp_global_p.h"
#include "../../general/hupnp_datatypes_p.h"

#include "../../dataelements/hudn.h"
#include "../../dataelements/hactioninfo.h"
#include "../../dataelements/hdeviceinfo.h"
#include "../../dataelements/hserviceinfo.h"

#include "../../general/hlogger_p.h"

#include <QtCore/QList>
#include <QtSoapMessage>

namespace Herqq
{

namespace Upnp
{

/*******************************************************************************
 * HActionProxy
 ******************************************************************************/
HActionProxy::HActionProxy(
    QNetworkAccessManager& nam, HClientActionPrivate* owner) :
        QObject(owner->q_ptr),
            m_locations(),
            m_iNextLocationToTry(0),
            m_nam(nam),
            m_reply(0),
            m_owner(owner)
{
    Q_ASSERT(m_owner);
}

HActionProxy::~HActionProxy()
{
}

void HActionProxy::error(QNetworkReply::NetworkError err)
{
    HLOG2(H_AT, H_FUN, m_owner->m_loggingIdentifier);

    if (!m_reply)
    {
        return;
    }

    if (err == QNetworkReply::RemoteHostClosedError)
    {
        return;
    }
    else if (err == QNetworkReply::ConnectionRefusedError ||
             err == QNetworkReply::HostNotFoundError)
    {
        HLOG_WARN(QString("Couldn't connect to the device [%1] @ [%2].").arg(
            m_owner->q_ptr->parentService()->parentDevice()->info().udn().toSimpleUuid(),
            m_locations[m_iNextLocationToTry].toString()));

        if (m_iNextLocationToTry < m_locations.size() - 1)
        {
            ++m_iNextLocationToTry;
            deleteReply();
            send();

            return;
        }

        HLOG_WARN("Action invocation failed: Couldn't connect to the device");
        m_iNextLocationToTry = 0;
    }

    HLOG_WARN(QString(
        "Action invocation failed: [%1]").arg(m_reply->errorString()));

    QVariant statusCode =
        m_reply->attribute(QNetworkRequest::HttpStatusCodeAttribute);

    invocationDone(statusCode.isValid() ? statusCode.toInt() : UpnpUndefinedFailure);
}

void HActionProxy::finished()
{
    HLOG2(H_AT, H_FUN, m_owner->m_loggingIdentifier);

    if (!m_reply)
    {
        return;
    }

    bool ok = false;
    qint32 statusCode = m_reply->attribute(
        QNetworkRequest::HttpStatusCodeAttribute).toInt(&ok);

    if (ok && statusCode != 200)
    {
        // the status code might not be available if the remote host closed
        // the connection.

        HLOG_WARN(QString(
            "Action invocation failed. Server responded: [%1, %2]").arg(
                QString::number(statusCode), m_reply->attribute(
                    QNetworkRequest::HttpReasonPhraseAttribute).toString()));

        invocationDone(statusCode);
        return;
    }

    QByteArray data = m_reply->readAll();
    QtSoapMessage response;
    if (!response.setContent(data))
    {
        HLOG_WARN(QString(
            "Received an invalid SOAP message as a response to "
            "action invocation: [%1]").arg(QString::fromUtf8(data)));

        invocationDone(UpnpUndefinedFailure);
        return;
    }

    if (response.isFault())
    {
        HLOG_WARN(QString(
            "Action invocation failed: [%1, %2]").arg(
                response.faultString().toString(),
                response.faultDetail().toString()));

        QtSoapType errCode = response.faultDetail()["errorCode"];
        invocationDone(errCode.isValid() ?
            errCode.value().toInt() : UpnpUndefinedFailure);
        return;
    }

    if (m_owner->m_info->outputArguments().size() == 0)
    {
        // since there are not supposed to be any out arguments, this is a
        // valid scenario
        invocationDone(UpnpSuccess);
        return;
    }

    const QtSoapType& root = response.method();
    if (!root.isValid())
    {
        HLOG_WARN(QString(
            "Received an invalid response to action invocation: [%1]").arg(
                response.toXmlString()));

        invocationDone(UpnpUndefinedFailure);
        return;
    }

    HActionArguments outArgs = m_owner->m_info->outputArguments();
    HActionArguments::const_iterator ci = outArgs.constBegin();
    for(; ci != outArgs.constEnd(); ++ci)
    {
        HActionArgument oarg = *ci;

        const QtSoapType& arg = root[oarg.name()];
        if (!arg.isValid())
        {
            invocationDone(UpnpUndefinedFailure);
            return;
        }

        HActionArgument userArg = outArgs.get(oarg.name());

        userArg.setValue(
            HUpnpDataTypes::convertToRightVariantType(
                arg.value().toString(), oarg.dataType()));
    }

    invocationDone(UpnpSuccess, &outArgs);
}

void HActionProxy::send()
{
    HLOG2(H_AT, H_FUN, m_owner->m_loggingIdentifier);

    Q_ASSERT(!invocationInProgress());

    if (m_locations.isEmpty())
    {
        // store the device locations only upon action invocation, and only
        // if they haven't been stored yet.
        m_locations = m_owner->q_ptr->parentService()->parentDevice()->locations(BaseUrl);
        Q_ASSERT(!m_locations.isEmpty());
        m_iNextLocationToTry = 0;
    }

    QtSoapNamespaces::instance().registerNamespace(
        "u", m_owner->q_ptr->parentService()->info().serviceType().toString());

    QtSoapMessage soapMsg;
    soapMsg.setMethod(
        QtSoapQName(
            m_owner->m_info->name(),
            m_owner->q_ptr->parentService()->info().serviceType().toString()));

    HActionArguments::const_iterator ci = m_inArgs.constBegin();
    for(; ci != m_inArgs.constEnd(); ++ci)
    {
        HActionArgument iarg = *ci;
        if (!m_inArgs.contains(iarg.name()))
        {
            invocationDone(UpnpInvalidArgs);
            return;
        }

        QtSoapType* soapArg =
            new SoapType(iarg.name(), iarg.dataType(), iarg.value());

        soapMsg.addMethodArgument(soapArg);
    }

    QNetworkRequest req;

    req.setHeader(
        QNetworkRequest::ContentTypeHeader,
        QString("text/xml; charset=\"utf-8\""));

    QString soapActionHdrField("\"");
    soapActionHdrField.append(
        m_owner->q_ptr->parentService()->info().serviceType().toString());

    soapActionHdrField.append("#").append(m_owner->m_info->name()).append("\"");
    req.setRawHeader("SOAPAction", soapActionHdrField.toUtf8());

    QUrl url = resolveUri(
        m_locations[m_iNextLocationToTry],
        m_owner->q_ptr->parentService()->info().controlUrl());

    req.setUrl(url);

    m_reply = m_nam.post(req, soapMsg.toXmlString().toUtf8());

    bool ok = connect(
        m_reply, SIGNAL(error(QNetworkReply::NetworkError)),
        this, SLOT(error(QNetworkReply::NetworkError)));
    Q_ASSERT(ok); Q_UNUSED(ok)

    ok = connect(m_reply, SIGNAL(finished()), this, SLOT(finished()));
    Q_ASSERT(ok);
}

/*******************************************************************************
 * HClientActionPrivate
 ******************************************************************************/
HClientActionPrivate::HClientActionPrivate() :
    m_loggingIdentifier(), q_ptr(0), m_info(),
    m_proxy(0), m_invocations()
{
}

HClientActionPrivate::~HClientActionPrivate()
{
}

void HClientActionPrivate::invokeCompleted(
    int rc, const HActionArguments* outArgs)
{
    Q_ASSERT(!m_invocations.isEmpty());

    HInvocationInfo inv = m_invocations.dequeue();

    inv.m_invokeId.setReturnValue(rc);
    inv.m_invokeId.setOutputArguments(outArgs ? *outArgs : HActionArguments());

    if (inv.execArgs.execType() != HExecArgs::FireAndForget)
    {
        bool sendEvent = true;
        if (inv.callback)
        {
            sendEvent = inv.callback(q_ptr, inv.m_invokeId);
        }

        if (sendEvent)
        {
            emit q_ptr->invokeComplete(q_ptr, inv.m_invokeId);
        }
    }

    if (!m_invocations.isEmpty() && !m_proxy->invocationInProgress())
    {
        const HInvocationInfo& inv = m_invocations.head();
        m_proxy->setInputArgs(inv.m_inArgs);
        m_proxy->send();
    }
}

bool HClientActionPrivate::setInfo(const HActionInfo& info)
{
    if (!info.isValid())
    {
        return false;
    }

    m_info.reset(new HActionInfo(info));
    return true;
}

/*******************************************************************************
 * HClientAction
 ******************************************************************************/
HClientAction::HClientAction(const HActionInfo& info, HClientService* parent) :
    QObject(reinterpret_cast<QObject*>(parent)),
        h_ptr(new HClientActionPrivate())
{
    Q_ASSERT_X(parent, H_AT, "Parent service must be defined.");
    Q_ASSERT_X(info.isValid(), H_AT, "Action information must be defined.");

    h_ptr->m_info.reset(new HActionInfo(info));
    h_ptr->q_ptr = this;
}

HClientAction::~HClientAction()
{
    delete h_ptr;
}

HClientService* HClientAction::parentService() const
{
    return reinterpret_cast<HClientService*>(parent());
}

const HActionInfo& HClientAction::info() const
{
    return *h_ptr->m_info;
}

HClientActionOp HClientAction::beginInvoke(
    const HActionArguments& inArgs, HExecArgs* execArgs)
{
    return beginInvoke(inArgs, HActionInvokeCallback(), execArgs);
}

HClientActionOp HClientAction::beginInvoke(
    const HActionArguments& inArgs,
    const HActionInvokeCallback& cb,
    HExecArgs* execArgs)
{
    HInvocationInfo inv(inArgs, cb, execArgs ? *execArgs : HExecArgs());
    h_ptr->m_invocations.enqueue(inv);

    if (!h_ptr->m_proxy->invocationInProgress())
    {
        h_ptr->m_proxy->setInputArgs(inArgs);
        h_ptr->m_proxy->send();
    }

    inv.m_invokeId.setReturnValue(UpnpInvocationInProgress);
    return inv.m_invokeId;
}

/*******************************************************************************
 * HDefaultClientAction
 ******************************************************************************/
HDefaultClientAction::HDefaultClientAction(
    const HActionInfo& info, HClientService* parent, QNetworkAccessManager& nam) :
        HClientAction(info, parent)
{
    h_ptr->m_proxy = new HActionProxy(nam, h_ptr);
}

}
}
