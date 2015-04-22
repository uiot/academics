/********************************************************************************
** Form generated from reading UI file 'device_window.ui'
**
** Created by: Qt User Interface Compiler version 4.8.6
**
** WARNING! All changes made in this file will be lost when recompiling UI file!
********************************************************************************/

#ifndef UI_DEVICE_WINDOW_H
#define UI_DEVICE_WINDOW_H

#include <QtCore/QVariant>
#include <QtGui/QAction>
#include <QtGui/QApplication>
#include <QtGui/QButtonGroup>
#include <QtGui/QHeaderView>
#include <QtGui/QMainWindow>
#include <QtGui/QMenuBar>
#include <QtGui/QSpacerItem>
#include <QtGui/QStatusBar>
#include <QtGui/QTextBrowser>
#include <QtGui/QVBoxLayout>
#include <QtGui/QWidget>
#include <QtGui/QLabel>

QT_BEGIN_NAMESPACE

class Ui_DeviceWindow
{
public:
    QWidget *centralwidget;
    QVBoxLayout *verticalLayout;
    QLabel *infoDisplay;
    QTextBrowser *statusDisplay;
    QSpacerItem *verticalSpacer;
    QMenuBar *menubar;
    QStatusBar *statusbar;

    void setupUi(QMainWindow *DeviceWindow)
    {
        if (DeviceWindow->objectName().isEmpty())
            DeviceWindow->setObjectName(QString::fromUtf8("DeviceWindow"));
        DeviceWindow->resize(486, 325);
        centralwidget = new QWidget(DeviceWindow);
        centralwidget->setObjectName(QString::fromUtf8("centralwidget"));
        verticalLayout = new QVBoxLayout(centralwidget);
        verticalLayout->setObjectName(QString::fromUtf8("verticalLayout"));
        infoDisplay = new QLabel(centralwidget);
        infoDisplay->setObjectName(QString::fromUtf8("infoDisplay"));
        infoDisplay->setAlignment(Qt::AlignCenter);
        infoDisplay->setFrameShape(QFrame::NoFrame);
        infoDisplay->setFrameShadow(QFrame::Plain);

        verticalLayout->addWidget(infoDisplay);

        statusDisplay = new QTextBrowser(centralwidget);
        statusDisplay->setObjectName(QString::fromUtf8("statusDisplay"));
        QSizePolicy sizePolicy1(QSizePolicy::Expanding, QSizePolicy::Expanding);
        sizePolicy1.setHorizontalStretch(0);
        sizePolicy1.setVerticalStretch(0);
        sizePolicy1.setHeightForWidth(statusDisplay->sizePolicy().hasHeightForWidth());
        statusDisplay->setSizePolicy(sizePolicy1);
        statusDisplay->setFrameShape(QFrame::Box);
        statusDisplay->setFrameShadow(QFrame::Plain);

        verticalLayout->addWidget(statusDisplay);

        verticalSpacer = new QSpacerItem(20, 29, QSizePolicy::Minimum, QSizePolicy::Fixed);

        verticalLayout->addItem(verticalSpacer);

        DeviceWindow->setCentralWidget(centralwidget);

        retranslateUi(DeviceWindow);

        QMetaObject::connectSlotsByName(DeviceWindow);
    } // setupUi

    void retranslateUi(QMainWindow *DeviceWindow)
    {
        DeviceWindow->setWindowTitle(QApplication::translate("DeviceWindow", "Hosting a simple HDevice", 0, QApplication::UnicodeUTF8));
        infoDisplay->setText(QApplication::translate("DeviceWindow", "Interaction Log", 0, QApplication::UnicodeUTF8));
    } // retranslateUi

};

namespace Ui {
    class DeviceWindow: public Ui_DeviceWindow {};
} // namespace Ui

QT_END_NAMESPACE

#endif // UI_DEVICE_WINDOW_H
