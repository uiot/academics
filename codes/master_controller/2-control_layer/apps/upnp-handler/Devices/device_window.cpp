/*
 *  Copyright (C) 2010, 2011 Tuomo Penttinen, all rights reserved.
 *
 *  Author: Tuomo Penttinen <tp@herqq.org>
 *
 *  This file is part of an application named HUpnpSimpleTestApp
 *  used for demonstrating how to use the Herqq UPnP (HUPnP) library.
 *
 *  HUpnpSimpleTestApp is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  HUpnpSimpleTestApp is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with HUpnpSimpleTestApp. If not, see <http://www.gnu.org/licenses/>.
 */

#include "device_window.h"
#include "ui_device_window.h"
#include "../Database/database.h"
#include "../Communication_Layer_Handler/To_Communication_Layer.h"

#include <HUpnpCore/HUpnp>
#include <HUpnpCore/HUdn>
#include <HUpnpCore/HServiceId>
#include <HUpnpCore/HDeviceHost>
#include <HUpnpCore/HDeviceInfo>
#include <HUpnpCore/HActionInfo>
#include <HUpnpCore/HServiceInfo>
#include <HUpnpCore/HServerAction>
#include <HUpnpCore/HActionArguments>
#include <HUpnpCore/HServerStateVariable>
#include <HUpnpCore/HDeviceHostConfiguration>

using namespace Herqq::Upnp;

/*******************************************************************************
 * HGenericService
 *******************************************************************************/
HGenericService::HGenericService(int id_val)
{
    id = id_val;
    xbee_addr = db.getServiceXbeeAddr(id);
}

HGenericService::~HGenericService()
{
}

HServerService::HActionInvokes HGenericService::createActionInvokes()
{
    HActionInvokes retVal;
    //receive actions
    log("Creating actions invokes!!!");
    QStringList actions = db.getActions(id);

    //generates actions hash
    for (auto it = actions.constBegin(); it != actions.constEnd(); ++it){
        retVal.insert(*it, HActionInvoke(this, &HGenericService::doAction));
    }
    //return actions hash
    return retVal;
}
qint32 HGenericService::doAction(const QString& action_name, const HActionArguments& inArgs, HActionArguments* outArgs)
{
    try{
    //treats each inArg per time.
    for (int i = 0; i < inArgs.size(); ++i) {
        //receives information of inArg argument name and argument value
        this->id;
        action_name;

        //Gets basic info about action and svar
        HStateVariableInfo svar = inArgs.get(i).relatedStateVariable();
        QString arg_name = inArgs.get(i).name();

        //retrives state variable from DB and fiz case Boolean HIGH LOW
        StringMap sv =  db.getStateVariable(this->id,svar.name());
        QVariant arg_value = inArgs.get(i).value().toString();

        //Sets value of state variable to sent argument value and makes eventing.
        setValue(svar.name(),arg_value);

        //prepares command to ZigBee/XBee script
        QString pin_number = sv["IN_Writing_Circuit_Pin"];
        QString data_type =  sv["EN_Data_Type"].toUpper();
        QString pin_type =  sv["EN_Writing_Circuit_Type"];
        QString baudrate =  sv["EN_Writing_Circuit_Baudrate"];

        //Fix for digital boolean to arduino
        QString send_val = arg_value.toString();
        if(sv["EN_Data_Type"].toUpper() == "BOOLEAN" && arg_value.toString() == "true")
            send_val =  QString("HIGH");
        else if(sv["EN_Data_Type"].toUpper() == "BOOLEAN" && arg_value.toString() == "false")
            send_val = QString("LOW");


        QString value = send_val;
        QStringList cmd_arguments;
        To_Communication_Layer CLC("127.0.0.1","CTRL_Client_to_send_request_to_COMM");

            bool ok;
            QJson::Parser json_decoder;
            QByteArray ba = CLC.send_control_request(xbee_addr,pin_number,pin_type,baudrate,data_type,value);
            QString cmd_result = QString::fromAscii(ba);
            log("COMM Layer response:");
            log(cmd_result);
            QVariantMap inJson = json_decoder.parse(ba,&ok).toMap();
            if(!ok){
                log("Unable do decode COMM Layer response json. Is XBEE module connected to master controller? is slave controller up and running?");
                return UpnpActionFailed;
            }

            //Prints values on device window
            emit actionInvoked(QString("%1").arg(action_name), QString("Service '%1' Action '%2' Argument '%3' was set to '%4'. ZigBee response: %5").arg(this->id).arg(action_name).arg(arg_name).arg(arg_value.toString()).arg(cmd_result));

            //returns bad response for this action invoke
            if(inJson["status"]==QString("fail")){
                log("Zigbee json response was: fail.");
                return UpnpActionFailed;
            }
        //Treats the out args
        (*outArgs)[arg_name].setValue(arg_value);
    }



    //Logs the invocation
    log(QString("invoked action '%1' from service '%2'").arg(action_name).arg(this->id));


    //Return success case all arguments were passed and received from Xbee Module
    return UpnpSuccess;
    }catch(std::exception& e){
        qDebug() << e.what();
        return UpnpActionFailed;
    }
}

/*******************************************************************************
 * HGenericDevice
 ******************************************************************************/
HGenericDevice::HGenericDevice(QString udn):
    HServerDevice()
{
    device_udn = udn;
    QStringList info = db.getDeviceByUdn(udn);
    device_id = info[0];
    device_type = info[1];
    device_xml_uri = info[2];
}

HGenericDevice::~HGenericDevice()
{
}

/*******************************************************************************
 * DeviceWindow
 *******************************************************************************/
namespace
{
class Creator :
        public HDeviceModelCreator
{
protected:

    virtual Creator* newInstance() const
    {
        return new Creator();
    }

public:

    virtual HServerDevice* createDevice(const HDeviceInfo& info) const
    {
        return new HGenericDevice(info.udn().toString());
    }

    virtual HServerService* createService(
            const HServiceInfo& serviceInfo, const HDeviceInfo& deviceInfo) const
    {

        int id = db.getServiceId(deviceInfo.udn().toString(),serviceInfo.serviceId().toString());
        return new HGenericService(id);
    }
};
}

DeviceWindow::DeviceWindow(QWidget *parent, QStringList d_info) : QMainWindow(parent), m_ui(new Ui::DeviceWindow), m_deviceHost(0), m_testDevice(){
    //Save and log device info
    device_info = d_info;
    log(d_info);
    QString description_xml = d_info[3];
    m_ui->setupUi(this);

    HDeviceHostConfiguration hostConfiguration;

    Creator creator;
    hostConfiguration.setDeviceModelCreator(creator);

    //Setup device info
    HDeviceConfiguration config;

    config.setPathToDeviceDescription(description_xml);

    config.setCacheControlMaxAge(30);

    hostConfiguration.add(config);

    m_deviceHost = new HDeviceHost(this);

    if (!m_deviceHost->init(hostConfiguration))
    {
        qWarning() << m_deviceHost->errorDescription();
        Q_ASSERT(false);
        return;
    }

    // Get device to add services
    // since we know there is at least one device if the initialization succeeded...
    m_testDevice = m_deviceHost->rootDevices().at(0);

    //for each service...
    QStringList Services_Id = db.getServicesByDeviceId(d_info[0].toInt());

    for (auto it = Services_Id.constBegin(); it != Services_Id.constEnd(); ++it){

        HServerService* service = m_testDevice->serviceById(HServiceId(*it));

        // our user interface is supposed to react when our actions are invoked, so
        // let's connect the signal introduced in HGenericService to this class.
        bool ok = connect(service, SIGNAL(actionInvoked(QString, QString)),this, SLOT(actionInvoked(QString, QString))); Q_ASSERT(ok); Q_UNUSED(ok)
    }
}

DeviceWindow::~DeviceWindow()
{
    delete m_ui;
    delete m_deviceHost;
}

void DeviceWindow::actionInvoked(const QString& actionName, const QString& text)
{
    //Logs action into window
    QString textToDisplay = QString("%1 Action [%2] invoked. Message: %3").arg(QDateTime::currentDateTime().toString(), actionName, text);
    m_ui->statusDisplay->append(textToDisplay);
}

void DeviceWindow::changeEvent(QEvent *e)
{
    QMainWindow::changeEvent(e);
    switch (e->type()) {
    case QEvent::LanguageChange:
        m_ui->retranslateUi(this);
        break;
    default:
        break;
    }
}

void DeviceWindow::closeEvent(QCloseEvent*)
{
    emit closed();
}
