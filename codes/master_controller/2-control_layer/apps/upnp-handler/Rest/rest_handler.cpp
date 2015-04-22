#include "rest_handler.h"
#include "QDebug"

Rest_Handler::Rest_Handler(QObject *parent) : QTcpServer(parent){

}
void Rest_Handler::StartServer()
{
    if(!this->listen(QHostAddress::Any,config_port))
    {
        qDebug() << "Could not start server";
    }
    else
    {
        qDebug() << "Listening...";
    }
}
QByteArray Rest_Handler::Process_Request(QByteArray request_json_string){
    int socketDescriptor = 0;
    qDebug() << " Executing straight action...";
    Thread_Handler *thread = new Thread_Handler(socketDescriptor, this);
    thread->services = &services;
    thread->request = request_json_string;
    return thread->Process_Request();
}
void Rest_Handler::incomingConnection(int socketDescriptor)
{
    qDebug() << socketDescriptor << " Connecting...";
    Thread_Handler *thread = new Thread_Handler(socketDescriptor, this);
    connect(thread, SIGNAL(finished()), thread, SLOT(deleteLater()));
    thread->services = &services;
    thread->start();

}
