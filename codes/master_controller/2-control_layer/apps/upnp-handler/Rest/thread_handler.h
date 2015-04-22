#ifndef THREAD_HANDLER_H
#define THREAD_HANDLER_H
//HUPnP
#include <HUpnpCore/HUpnp>
#include <HUpnpCore/HActionInfo>
#include <HUpnpCore/HClientAction>
#include <HUpnpCore/HClientDevice>
#include <HUpnpCore/HClientService>
#include <HUpnpCore/HActionArguments>
#include <HUpnpCore/HClientActionOp>
#include <HUpnpCore/HClientStateVariable>
#include <HUpnpCore/HUdn>
#include <HUpnpCore/HDeviceInfo>
#include <HUpnpCore/HControlPoint>
#include <HUpnpCore/HStateVariableInfo>
#include <HUpnpCore/HStateVariableEvent>
#include <HUpnpCore/HControlPointConfiguration>
#include <HUpnpCore/HServerDevice>
#include <HUpnpCore/HServerService>
#include <HUpnpCore/HServiceId>
#include <HUpnpCore/HDeviceHost>
#include <HUpnpCore/HServiceInfo>
#include <HUpnpCore/HServerAction>
#include <HUpnpCore/HServerStateVariable>
#include <HUpnpCore/HDeviceHostConfiguration>
#include <HUpnpCore/HUpnpDataTypes>
//QJson
#include <qjson/serializer.h>
#include <qjson/parser.h>
#include <QThread>
#include <QTcpSocket>
#include <QDebug>
#include "../Database/database.h"

using namespace Herqq::Upnp;

class Thread_Handler : public QThread{
    friend class Rest_Handler;
    Q_OBJECT
public:
    explicit Thread_Handler(int iID, QObject *parent);
    void run();
    QByteArray request;

signals:
    void error(QTcpSocket::SocketError socketerror);

public slots:
    void readyRead();
    void disconnected();

public slots:

protected:
    QByteArray Process_Request();
    QMap<QString,HServerDevice*>* services;
    HServerAction* action;
private:
    QTcpSocket *socket;
    int socketDescriptor;

};

extern bool is_zigbee_free;
#endif // THREAD_HANDLER_H
