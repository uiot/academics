/********************************************************************************
** Form generated from reading UI file 'mainwindow.ui'
**
** Created by: Qt User Interface Compiler version 4.8.6
**
** WARNING! All changes made in this file will be lost when recompiling UI file!
********************************************************************************/

#ifndef UI_MAINWINDOW_H
#define UI_MAINWINDOW_H

#include <QtCore/QVariant>
#include <QtGui/QAction>
#include <QtGui/QApplication>
#include <QtGui/QButtonGroup>
#include <QtGui/QHeaderView>
#include <QtGui/QMainWindow>
#include <QtGui/QMenuBar>
#include <QtGui/QPushButton>
#include <QtGui/QStatusBar>
#include <QtGui/QVBoxLayout>
#include <QtGui/QWidget>

QT_BEGIN_NAMESPACE

class Ui_MainWindow
{
public:
    QWidget *centralwidget;
    QVBoxLayout *verticalLayout;
    QPushButton *hostDeviceButton;
    QPushButton *startControlPointButton;
    QMenuBar *menubar;
    QStatusBar *statusbar;

    void setupUi(QMainWindow *MainWindow)
    {
        if (MainWindow->objectName().isEmpty())
            MainWindow->setObjectName(QString::fromUtf8("MainWindow"));
        MainWindow->resize(250, 111);
        QSizePolicy sizePolicy(QSizePolicy::Fixed, QSizePolicy::Fixed);
        sizePolicy.setHorizontalStretch(0);
        sizePolicy.setVerticalStretch(0);
        sizePolicy.setHeightForWidth(MainWindow->sizePolicy().hasHeightForWidth());
        MainWindow->setSizePolicy(sizePolicy);
        MainWindow->setMinimumSize(QSize(250, 100));
        MainWindow->setMaximumSize(QSize(250, 111));
        centralwidget = new QWidget(MainWindow);
        centralwidget->setObjectName(QString::fromUtf8("centralwidget"));
        verticalLayout = new QVBoxLayout(centralwidget);
        verticalLayout->setObjectName(QString::fromUtf8("verticalLayout"));
        hostDeviceButton = new QPushButton(centralwidget);
        hostDeviceButton->setObjectName(QString::fromUtf8("hostDeviceButton"));

        verticalLayout->addWidget(hostDeviceButton);

        startControlPointButton = new QPushButton(centralwidget);
        startControlPointButton->setObjectName(QString::fromUtf8("startControlPointButton"));

        verticalLayout->addWidget(startControlPointButton);

        MainWindow->setCentralWidget(centralwidget);
        menubar = new QMenuBar(MainWindow);
        menubar->setObjectName(QString::fromUtf8("menubar"));
        menubar->setGeometry(QRect(0, 0, 250, 22));
        MainWindow->setMenuBar(menubar);
        statusbar = new QStatusBar(MainWindow);
        statusbar->setObjectName(QString::fromUtf8("statusbar"));
        MainWindow->setStatusBar(statusbar);

        retranslateUi(MainWindow);

        QMetaObject::connectSlotsByName(MainWindow);
    } // setupUi

    void retranslateUi(QMainWindow *MainWindow)
    {
        MainWindow->setWindowTitle(QApplication::translate("MainWindow", "[UIoT] Control Layer Manager", 0, QApplication::UnicodeUTF8));
#ifndef QT_NO_TOOLTIP
        hostDeviceButton->setToolTip(QApplication::translate("MainWindow", "Starts an HDeviceHost and instructs it to host a simple test HDevice", 0, QApplication::UnicodeUTF8));
#endif // QT_NO_TOOLTIP
        hostDeviceButton->setText(QApplication::translate("MainWindow", "Start Logical Devices", 0, QApplication::UnicodeUTF8));
#ifndef QT_NO_TOOLTIP
        startControlPointButton->setToolTip(QApplication::translate("MainWindow", "Starts an HControlPoint for monitoring UPnP devices on the network", 0, QApplication::UnicodeUTF8));
#endif // QT_NO_TOOLTIP
        startControlPointButton->setText(QApplication::translate("MainWindow", "Run a UPnP Control Point For testing", 0, QApplication::UnicodeUTF8));
    } // retranslateUi

};

namespace Ui {
    class MainWindow: public Ui_MainWindow {};
} // namespace Ui

QT_END_NAMESPACE

#endif // UI_MAINWINDOW_H
