#ifndef FROM_COMMUNICATION_LAYER_H
#define FROM_COMMUNICATION_LAYER_H



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
#include <QTcpServer>
#include <QDebug>
#include "../Database/database.h"

using namespace Herqq::Upnp;

class From_Communication_Layer : public QTcpServer{
    friend class MainWindow;
    Q_OBJECT
public:
    explicit From_Communication_Layer(QObject *parent = 0);
    void StartServer();
protected:
    QMap<QString,Herqq::Upnp::HServerDevice*> services;
    void incomingConnection(int socketDescriptor);
signals:

public slots:

private:
    const static int config_max_threads = 10;
    const static int config_port = 12937;
    QByteArray Process_Request(QByteArray request_json_string);
};

class Thread_Handler_2 : public QThread{
    friend class From_Communication_Layer ;
    Q_OBJECT
public:
    explicit Thread_Handler_2(int iID, QObject *parent);
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




#endif // FROM_COMMUNICATION_LAYER_H
