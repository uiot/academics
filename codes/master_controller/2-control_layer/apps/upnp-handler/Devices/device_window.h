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

#ifndef DEVICE_WINDOW_H
#define DEVICE_WINDOW_H

#include <HUpnpCore/HUpnp>
#include <HUpnpCore/HServerDevice>
#include <HUpnpCore/HServerService>

#include <QtGui/QMainWindow>
#include <QtSql/QtSql>
#include <qjson/parser.h>

namespace Ui {
    class DeviceWindow;
}

class DeviceWindow;

//
// Test HService created by the HGenericDevice. This is the only service
// exposed by the HGenericDevice
//
class HGenericService :
    public Herqq::Upnp::HServerService
{
Q_OBJECT
Q_DISABLE_COPY(HGenericService)

private:
    int id;
    QString xbee_addr;
    virtual HActionInvokes createActionInvokes();
    QStringList ActionsSql();
public:
    HGenericService (int id_val);
    virtual ~HGenericService();

    //
    // The following methods could very well be private. In fact, they
    // could be regular functions or functors defined elsewhere as well.
    // It is a design decision whether you wish your service type to be used directly,
    // in which case exposing its UPnP actions as methods of the service
    // like in the following case ease the use of the class.
    qint32 doAction(
            const QString& action_name,
            const Herqq::Upnp::HActionArguments& inArgs,
            Herqq::Upnp::HActionArguments* outArgs = 0);

Q_SIGNALS:

    void actionInvoked(const QString& actionName, const QString& text);
};

//
// A class created internally to represent our UPnP test device.
//
class HGenericDevice :
    public Herqq::Upnp::HServerDevice
{
Q_OBJECT
Q_DISABLE_COPY(HGenericDevice)

public:
    QStringList ServicesSql();
    explicit HGenericDevice(QString udn);
    virtual ~HGenericDevice();
    QString device_udn;
    QString device_type;
    QString device_id;
    QString device_xml_uri;
    int id;
};

//
//
//
class DeviceWindow :
    public QMainWindow
{
   // added to allow to get test_device(holder of services)
    friend class MainWindow;
Q_OBJECT
Q_DISABLE_COPY(DeviceWindow)

private:
    Ui::DeviceWindow* m_ui;
    QStringList device_info;


    Herqq::Upnp::HDeviceHost* m_deviceHost;
    // ^^ This is needed to host the HGenericDevice


protected:
    Herqq::Upnp::HServerDevice* m_testDevice;
    // A root HServerDevice hosted by a HDeviceHost will not be deleted
    // until the device host is shutdown or deleted. The root device is stored
    // here just for this example to demonstrate that you may use the
    // hosted HServerDevice directly while control points may be
    // accessing it through the network.
    virtual void changeEvent(QEvent*);
    virtual void closeEvent(QCloseEvent*);

public:

    explicit DeviceWindow(QWidget *parent = 0, QStringList d_info = QStringList());
    virtual ~DeviceWindow();

private slots:
    void actionInvoked(const QString&, const QString&);

Q_SIGNALS:

    void closed();
};

#endif // DEVICE_WINDOW_H
