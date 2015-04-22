#ifndef REST_HANDLER_H
#define REST_HANDLER_H
//QTCP and QThread
#include <QTcpServer>
//1: #include <QThreadPool>
#include <QDebug>
#include "thread_handler.h"
//2: #include <QObject>
//2: #include <QTcpSocket>

using namespace Herqq::Upnp;

class  Rest_Handler : public QTcpServer{
    friend class MainWindow;
    Q_OBJECT
public:
    explicit Rest_Handler(QObject *parent = 0);
    void StartServer();
protected:
    QMap<QString,Herqq::Upnp::HServerDevice*> services;
    void incomingConnection(int socketDescriptor);
signals:

public slots:

private:
    const static int config_max_threads = 10;
    const static int config_port = 12936;
    QByteArray Process_Request(QByteArray request_json_string);
};

#endif // REST_HANDLER_H
