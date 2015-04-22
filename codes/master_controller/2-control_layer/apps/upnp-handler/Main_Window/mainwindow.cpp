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

#include "mainwindow.h"
#include "ui_mainwindow.h"

#include "../Devices/device_window.h"
#include "../Control_Point/controlpoint_window.h"

#include "../Database/database.h"
#include "../Rest/rest_handler.h"
#include "../Communication_Layer_Handler/From_Communication_Layer.h"

MainWindow::MainWindow(QWidget* parent) :
    QMainWindow(parent),
    m_ui(new Ui::MainWindow)
{
    //QWidget::setWindowIcon(QIcon("logos/icon.icns"));
    m_ui->setupUi(this);
}

MainWindow::~MainWindow()
{
    delete m_ui;
}

void MainWindow::changeEvent(QEvent* e)
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

void MainWindow::deviceWindowClosed()
{
    m_ui->hostDeviceButton->setEnabled(true);
}


void MainWindow::on_hostDeviceButton_clicked()
{
    //Get alld evices from database
    QList<QStringList> devices_info = db.getDevices();
    QMap<QString,Herqq::Upnp::HServerDevice*> all_services;
    Rest_Handler* rest = new Rest_Handler();
    From_Communication_Layer* FCL = new From_Communication_Layer();

    //For each device, creates a HUpnP Device and connect it
    for (auto it = devices_info.constBegin(); it != devices_info.constEnd(); ++it){
        try{
            //start device
            QStringList info = *it;
            DeviceWindow* dw = new DeviceWindow(this,info);
            QString device_id = info[0];
            all_services[device_id] = dw->m_testDevice;

            //setup ui
            bool ok = connect(dw, SIGNAL(closed()), dw, SLOT(deleteLater()));

            Q_ASSERT(ok); Q_UNUSED(ok);

            ok = connect(dw, SIGNAL(closed()), this, SLOT(deviceWindowClosed()));

            Q_ASSERT(ok);
            QString d_name = QString("Device %1 -  %2").arg(info[0]).arg(info[4]);
            dw->setWindowTitle(d_name);

            //Shows device window
            dw->show();
        }catch(QtConcurrent::Exception e){

        }
    }
    rest->services = all_services;
    FCL->services = all_services;
    rest->StartServer();
    FCL->StartServer();
    m_ui->hostDeviceButton->setEnabled(false);
    //QByteArray ba;
    //ba.append(QString("{\"device\":\"13\",\"service\":\"urn:hamn:serviceId:power_handler:18\",\"action\":\"set_power\",\"arguments\":{\"wait_before_change\":243,\"set_power_to\":\"on\"}}"));
    //qDebug() << rest->Process_Request(ba);
}

void MainWindow::on_startControlPointButton_clicked(){
    ControlPointWindow* cpw = new ControlPointWindow(this);
    bool ok = connect(cpw, SIGNAL(closed()), cpw, SLOT(deleteLater()));
    Q_ASSERT(ok); Q_UNUSED(ok);
    cpw->show();
}
