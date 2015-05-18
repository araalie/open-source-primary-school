<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!class_exists('LogAction'))
{
	class LogAction
{
	const Login = 1;
	const Logout = 2;  
	const AddStudent = 3;
	const EditStudent = 4;
	const Error =5;
	const AddUser =6;
	const EditUser = 7;
	const CreateStaff = 9;	
	const EditStaff = 10;
	const Info = 11;
	const EditTerm = 12;
	const UpdateTerm = 13;
	const CreateYear = 14;
	const EditYear = 15;
	const CreateExamSet = 16;
	const EditExamSet = 17;
	const CreateIncome = 18;
	const EditIncome = 19;
	const CreateExpense = 20;
	const EditExpense = 21;
	const CreateIncomeCategory = 22;
	const EditIncomeCategory = 23;
	const CreateExpensesCategory = 24;
	const EditExpensesCategory = 25;
	const AccountActivity = 26;
	const AccountError = 27;
	const Fees = 28;
	const Grading = 29;
	const Exam = 30;
	const FeesProfileActivity = 31;
	const DeleteFeesEntry = 32;
	const Reports = 33;
	const UserAccountActivity = 34;
	const BursaryOperation = 35;
	const Trace = 36;
	const DebtOperation = 37;
	const FeesSummaryOp = 38;
	const TermSwitch = 40;
}

}
