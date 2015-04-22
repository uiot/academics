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

#include "hserviceid.h"

#include "../general/hlogger_p.h"
#include "../utils/hmisc_utils_p.h"

#include <QtCore/QString>
#include <QtCore/QStringList>

namespace Herqq
{

namespace Upnp
{

/*******************************************************************************
 * HServiceIdPrivate
 ******************************************************************************/
class HServiceIdPrivate
{
public:

    QString m_suffix;
    QStringList m_elements;

public:

    HServiceIdPrivate() :
        m_suffix(), m_elements()
    {
    }

    HServiceIdPrivate(const QString& arg) :
        m_suffix(), m_elements()
    {
        HLOG(H_AT, H_FUN);

        QStringList tmp = arg.simplified().split(":");
        if (tmp.size() < 4)
        {
            HLOG_WARN_NONSTD(
                QString("Invalid service identifier [%1]").arg(arg));

            return;
        }

        if (tmp[0].compare("urn", Qt::CaseInsensitive) != 0)
        {
            HLOG_WARN_NONSTD(
                QString("Invalid service identifier [%1]").arg(arg));

            return;
        }

        if (tmp[1].compare("upnp-org", Qt::CaseInsensitive) != 0)
        {
            tmp[1] = tmp[1].replace('.', '-');
            if (tmp[1].isEmpty())
            {
                HLOG_WARN_NONSTD(QString(
                    "Invalid service identifier [%1]").arg(arg));

                return;
            }
        }

        bool warned = false;
        if (tmp[2].compare("serviceId", Qt::CaseInsensitive) != 0)
        {
            HLOG_WARN_NONSTD(QString("Invalid service identifier [%1]").arg(arg));
            warned = true;
            // at least some Intel software fails to specify this right
        }

        if (tmp[3].isEmpty())
        {
            if (!warned)
            {
                HLOG_WARN(QString("Invalid service identifier [%1]").arg(arg));
            }
            return;
        }

        m_suffix = tmp[3];
        for (qint32 i = 4; i < tmp.size(); ++i)
        {
            m_suffix.append(':').append(tmp[i]);
        }

        m_elements = tmp;
    }

    ~HServiceIdPrivate()
    {
    }
};

/*******************************************************************************
 * HServiceId
 ******************************************************************************/
HServiceId::HServiceId() :
    h_ptr(new HServiceIdPrivate())
{
}

HServiceId::HServiceId(const QString& serviceId) :
    h_ptr(new HServiceIdPrivate(serviceId))
{
}

HServiceId::HServiceId(const HServiceId& other) :
    h_ptr(0)
{
    Q_ASSERT(&other != this);
    h_ptr = new HServiceIdPrivate(*other.h_ptr);
}

HServiceId& HServiceId::operator=(const HServiceId& other)
{
    Q_ASSERT(&other != this);

    HServiceIdPrivate* newHptr = new HServiceIdPrivate(*other.h_ptr);
    delete h_ptr;
    h_ptr = newHptr;

    return *this;
}

HServiceId::~HServiceId()
{
    delete h_ptr;
}

bool HServiceId::isValid(HValidityCheckLevel checkLevel) const
{
    if (checkLevel == LooseChecks)
    {
        return !h_ptr->m_suffix.isEmpty();
    }

    return h_ptr->m_elements.size() >= 4 &&
           h_ptr->m_elements[0] == "urn" &&
           h_ptr->m_elements[2] == "serviceId";
}

bool HServiceId::isStandardType() const
{
    if (!isValid(LooseChecks))
    {
        return false;
    }

    return h_ptr->m_elements[1] == "upnp-org";
}

QString HServiceId::urn(bool completeUrn) const
{
    if (!isValid(LooseChecks))
    {
        return QString();
    }

    QString retVal;
    if (completeUrn)
    {
        retVal.append("urn:");
    }

    retVal.append(h_ptr->m_elements[1]);

    return retVal;
}

QString HServiceId::suffix() const
{
    if (!isValid(LooseChecks))
    {
        return QString();
    }

    return h_ptr->m_suffix;
}

QString HServiceId::toString() const
{
    return h_ptr->m_elements.join(":");
}

bool operator==(const HServiceId& sid1, const HServiceId& sid2)
{
    // This check is made first, since most often it is the suffix that is
    // different, if anything.
    if (sid1.h_ptr->m_suffix != sid2.h_ptr->m_suffix)
    {
        return false;
    }

    // The rest of the checks are lengthy because the
    // "serviceId" component has to be ignored and the "domain" part cannot be
    // strictly compared...
    // The operator is supposed to test for logical equivalence and since
    // some notable UPnP software fails to specify both the serviceId and
    // the domain components right, they have to be ignored.

    QStringList elems1 = sid1.h_ptr->m_elements;
    QStringList elems2 = sid2.h_ptr->m_elements;
    if (elems1.size() != elems2.size())
    {
        return false;
    }

    for(qint32 i = 0; i < elems1.size() - 1; ++i)
    {
        if (i != 1 && i != 2)
        {
            if (elems1.at(i) != elems2.at(i))
            {
                return false;
            }
        }
    }

    return true;
}

quint32 qHash(const HServiceId& key)
{
    // See the comments within == operator. The serviceId component is not
    // part of the hash for the same reason.

    QString tmp;
    QStringList elems = key.h_ptr->m_elements;
    for(qint32 i = 0; i < elems.size() - 1; ++i)
    {
        if (i != 1 && i != 2)
        {
            tmp.append(elems.at(i));
        }
    }

    QByteArray data = tmp.toLocal8Bit();
    return hash(data.constData(), data.size());
}

}
}
