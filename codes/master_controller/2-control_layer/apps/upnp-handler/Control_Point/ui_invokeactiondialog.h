/********************************************************************************
** Form generated from reading UI file 'invokeactiondialog.ui'
**
** Created by: Qt User Interface Compiler version 4.8.6
**
** WARNING! All changes made in this file will be lost when recompiling UI file!
********************************************************************************/

#ifndef UI_INVOKEACTIONDIALOG_H
#define UI_INVOKEACTIONDIALOG_H

#include <QtCore/QVariant>
#include <QtGui/QAction>
#include <QtGui/QApplication>
#include <QtGui/QButtonGroup>
#include <QtGui/QDialog>
#include <QtGui/QGroupBox>
#include <QtGui/QHBoxLayout>
#include <QtGui/QHeaderView>
#include <QtGui/QPushButton>
#include <QtGui/QTableWidget>
#include <QtGui/QVBoxLayout>

QT_BEGIN_NAMESPACE

class Ui_InvokeActionDialog
{
public:
    QVBoxLayout *verticalLayout;
    QGroupBox *inputArgumentsBox;
    QHBoxLayout *horizontalLayout;
    QTableWidget *inputArguments;
    QGroupBox *outputArgumentsBox;
    QVBoxLayout *verticalLayout_2;
    QTableWidget *outputArguments;
    QPushButton *invokeButton;

    void setupUi(QDialog *InvokeActionDialog)
    {
        if (InvokeActionDialog->objectName().isEmpty())
            InvokeActionDialog->setObjectName(QString::fromUtf8("InvokeActionDialog"));
        InvokeActionDialog->setWindowModality(Qt::NonModal);
        InvokeActionDialog->resize(426, 420);
        InvokeActionDialog->setSizeGripEnabled(true);
        verticalLayout = new QVBoxLayout(InvokeActionDialog);
        verticalLayout->setObjectName(QString::fromUtf8("verticalLayout"));
        inputArgumentsBox = new QGroupBox(InvokeActionDialog);
        inputArgumentsBox->setObjectName(QString::fromUtf8("inputArgumentsBox"));
        horizontalLayout = new QHBoxLayout(inputArgumentsBox);
        horizontalLayout->setObjectName(QString::fromUtf8("horizontalLayout"));
        inputArguments = new QTableWidget(inputArgumentsBox);
        if (inputArguments->columnCount() < 3)
            inputArguments->setColumnCount(3);
        QTableWidgetItem *__qtablewidgetitem = new QTableWidgetItem();
        inputArguments->setHorizontalHeaderItem(0, __qtablewidgetitem);
        QTableWidgetItem *__qtablewidgetitem1 = new QTableWidgetItem();
        inputArguments->setHorizontalHeaderItem(1, __qtablewidgetitem1);
        QTableWidgetItem *__qtablewidgetitem2 = new QTableWidgetItem();
        inputArguments->setHorizontalHeaderItem(2, __qtablewidgetitem2);
        inputArguments->setObjectName(QString::fromUtf8("inputArguments"));
        inputArguments->horizontalHeader()->setCascadingSectionResizes(false);
        inputArguments->horizontalHeader()->setStretchLastSection(true);
        inputArguments->verticalHeader()->setVisible(false);

        horizontalLayout->addWidget(inputArguments);


        verticalLayout->addWidget(inputArgumentsBox);

        outputArgumentsBox = new QGroupBox(InvokeActionDialog);
        outputArgumentsBox->setObjectName(QString::fromUtf8("outputArgumentsBox"));
        verticalLayout_2 = new QVBoxLayout(outputArgumentsBox);
        verticalLayout_2->setObjectName(QString::fromUtf8("verticalLayout_2"));
        outputArguments = new QTableWidget(outputArgumentsBox);
        if (outputArguments->columnCount() < 3)
            outputArguments->setColumnCount(3);
        QTableWidgetItem *__qtablewidgetitem3 = new QTableWidgetItem();
        outputArguments->setHorizontalHeaderItem(0, __qtablewidgetitem3);
        QTableWidgetItem *__qtablewidgetitem4 = new QTableWidgetItem();
        outputArguments->setHorizontalHeaderItem(1, __qtablewidgetitem4);
        QTableWidgetItem *__qtablewidgetitem5 = new QTableWidgetItem();
        outputArguments->setHorizontalHeaderItem(2, __qtablewidgetitem5);
        outputArguments->setObjectName(QString::fromUtf8("outputArguments"));
        outputArguments->horizontalHeader()->setStretchLastSection(true);
        outputArguments->verticalHeader()->setVisible(false);

        verticalLayout_2->addWidget(outputArguments);


        verticalLayout->addWidget(outputArgumentsBox);

        invokeButton = new QPushButton(InvokeActionDialog);
        invokeButton->setObjectName(QString::fromUtf8("invokeButton"));
        invokeButton->setDefault(true);

        verticalLayout->addWidget(invokeButton);


        retranslateUi(InvokeActionDialog);

        QMetaObject::connectSlotsByName(InvokeActionDialog);
    } // setupUi

    void retranslateUi(QDialog *InvokeActionDialog)
    {
        InvokeActionDialog->setWindowTitle(QApplication::translate("InvokeActionDialog", "Testing action invocation", 0, QApplication::UnicodeUTF8));
        inputArgumentsBox->setTitle(QApplication::translate("InvokeActionDialog", "Input arguments", 0, QApplication::UnicodeUTF8));
        QTableWidgetItem *___qtablewidgetitem = inputArguments->horizontalHeaderItem(0);
        ___qtablewidgetitem->setText(QApplication::translate("InvokeActionDialog", "Data type", 0, QApplication::UnicodeUTF8));
        QTableWidgetItem *___qtablewidgetitem1 = inputArguments->horizontalHeaderItem(1);
        ___qtablewidgetitem1->setText(QApplication::translate("InvokeActionDialog", "Name", 0, QApplication::UnicodeUTF8));
        QTableWidgetItem *___qtablewidgetitem2 = inputArguments->horizontalHeaderItem(2);
        ___qtablewidgetitem2->setText(QApplication::translate("InvokeActionDialog", "Value", 0, QApplication::UnicodeUTF8));
        outputArgumentsBox->setTitle(QApplication::translate("InvokeActionDialog", "Output arguments", 0, QApplication::UnicodeUTF8));
        QTableWidgetItem *___qtablewidgetitem3 = outputArguments->horizontalHeaderItem(0);
        ___qtablewidgetitem3->setText(QApplication::translate("InvokeActionDialog", "Data type", 0, QApplication::UnicodeUTF8));
        QTableWidgetItem *___qtablewidgetitem4 = outputArguments->horizontalHeaderItem(1);
        ___qtablewidgetitem4->setText(QApplication::translate("InvokeActionDialog", "Name", 0, QApplication::UnicodeUTF8));
        QTableWidgetItem *___qtablewidgetitem5 = outputArguments->horizontalHeaderItem(2);
        ___qtablewidgetitem5->setText(QApplication::translate("InvokeActionDialog", "Value", 0, QApplication::UnicodeUTF8));
        invokeButton->setText(QApplication::translate("InvokeActionDialog", "&Invoke", 0, QApplication::UnicodeUTF8));
    } // retranslateUi

};

namespace Ui {
    class InvokeActionDialog: public Ui_InvokeActionDialog {};
} // namespace Ui

QT_END_NAMESPACE

#endif // UI_INVOKEACTIONDIALOG_H
