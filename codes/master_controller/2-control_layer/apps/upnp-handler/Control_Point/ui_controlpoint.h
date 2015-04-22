/********************************************************************************
** Form generated from reading UI file 'controlpoint.ui'
**
** Created by: Qt User Interface Compiler version 4.8.6
**
** WARNING! All changes made in this file will be lost when recompiling UI file!
********************************************************************************/

#ifndef UI_CONTROLPOINT_H
#define UI_CONTROLPOINT_H

#include <QtCore/QVariant>
#include <QtGui/QAction>
#include <QtGui/QApplication>
#include <QtGui/QButtonGroup>
#include <QtGui/QHeaderView>
#include <QtGui/QMainWindow>
#include <QtGui/QSplitter>
#include <QtGui/QTableView>
#include <QtGui/QTextBrowser>
#include <QtGui/QTreeView>
#include <QtGui/QVBoxLayout>
#include <QtGui/QWidget>

QT_BEGIN_NAMESPACE

class Ui_ControlPointWindow
{
public:
    QWidget *centralwidget;
    QVBoxLayout *verticalLayout;
    QSplitter *splitter;
    QTreeView *navigatorTreeView;
    QTableView *dataTableView;
    QWidget *widget;
    QVBoxLayout *verticalLayout_2;
    QTextBrowser *status;

    void setupUi(QMainWindow *ControlPointWindow)
    {
        if (ControlPointWindow->objectName().isEmpty())
            ControlPointWindow->setObjectName(QString::fromUtf8("ControlPointWindow"));
        ControlPointWindow->resize(674, 447);
        centralwidget = new QWidget(ControlPointWindow);
        centralwidget->setObjectName(QString::fromUtf8("centralwidget"));
        QSizePolicy sizePolicy(QSizePolicy::Expanding, QSizePolicy::Expanding);
        sizePolicy.setHorizontalStretch(0);
        sizePolicy.setVerticalStretch(0);
        sizePolicy.setHeightForWidth(centralwidget->sizePolicy().hasHeightForWidth());
        centralwidget->setSizePolicy(sizePolicy);
        verticalLayout = new QVBoxLayout(centralwidget);
        verticalLayout->setObjectName(QString::fromUtf8("verticalLayout"));
        splitter = new QSplitter(centralwidget);
        splitter->setObjectName(QString::fromUtf8("splitter"));
        sizePolicy.setHeightForWidth(splitter->sizePolicy().hasHeightForWidth());
        splitter->setSizePolicy(sizePolicy);
        splitter->setOrientation(Qt::Horizontal);
        navigatorTreeView = new QTreeView(splitter);
        navigatorTreeView->setObjectName(QString::fromUtf8("navigatorTreeView"));
        splitter->addWidget(navigatorTreeView);
        dataTableView = new QTableView(splitter);
        dataTableView->setObjectName(QString::fromUtf8("dataTableView"));
        dataTableView->setAlternatingRowColors(true);
        splitter->addWidget(dataTableView);
        dataTableView->horizontalHeader()->setStretchLastSection(true);
        dataTableView->verticalHeader()->setStretchLastSection(false);

        verticalLayout->addWidget(splitter);

        widget = new QWidget(centralwidget);
        widget->setObjectName(QString::fromUtf8("widget"));
        QSizePolicy sizePolicy1(QSizePolicy::Preferred, QSizePolicy::Preferred);
        sizePolicy1.setHorizontalStretch(0);
        sizePolicy1.setVerticalStretch(0);
        sizePolicy1.setHeightForWidth(widget->sizePolicy().hasHeightForWidth());
        widget->setSizePolicy(sizePolicy1);
        widget->setMaximumSize(QSize(16777215, 120));
        verticalLayout_2 = new QVBoxLayout(widget);
        verticalLayout_2->setSpacing(0);
        verticalLayout_2->setContentsMargins(0, 0, 0, 0);
        verticalLayout_2->setObjectName(QString::fromUtf8("verticalLayout_2"));
        status = new QTextBrowser(widget);
        status->setObjectName(QString::fromUtf8("status"));
        status->setFrameShape(QFrame::Box);
        status->setFrameShadow(QFrame::Plain);

        verticalLayout_2->addWidget(status);


        verticalLayout->addWidget(widget);

        ControlPointWindow->setCentralWidget(centralwidget);

        retranslateUi(ControlPointWindow);

        QMetaObject::connectSlotsByName(ControlPointWindow);
    } // setupUi

    void retranslateUi(QMainWindow *ControlPointWindow)
    {
        ControlPointWindow->setWindowTitle(QApplication::translate("ControlPointWindow", "Testing HControlPoint", 0, QApplication::UnicodeUTF8));
    } // retranslateUi

};

namespace Ui {
    class ControlPointWindow: public Ui_ControlPointWindow {};
} // namespace Ui

QT_END_NAMESPACE

#endif // UI_CONTROLPOINT_H
