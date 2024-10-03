<?php


use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Crm\EmailCampaignController;
use App\Http\Controllers\Crm\EmailTemplateController;
use App\Http\Controllers\webControllers\IndividualController;
use App\Http\Controllers\Ticket\EmailController;
use App\Http\Controllers\BusinessIntelligence\BIController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\webControllers\Accountant_BookkeeperController;
use App\Http\Controllers\webControllers\BusinessController;
use App\Http\Controllers\Documents\DocumentController;
use App\Http\Controllers\Hcm\DepartmentController;
use App\Http\Controllers\webControllers\IndividualBusinessController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', 'AuthenticationController@homemain');
// Route::('individual/login', 'web.Auth.individuallogin')->name('individual.login');
// Route::post('/individual/create', [IndividualController::class, 'individual_Create'])->name('individual.create');
Route::get('individual', 'webControllers\IndividualController@individualReg')->name('individual.login');
Route::post('individual/create', 'webControllers\IndividualController@individualCreate')->name('individual.create');

// Route::get('individual/login', function () {
//     return redirect('individual/login');
Route::get('individual', 'webControllers\IndividualController@individualReg')->name('individual.login');

Route::post('individualbusiness/create', 'webControllers\IndividualBusinessController@individualCreate')->name('individualbusiness.create');
// Route::View('individualbusiness', 'web.individualbusiness')->name('individualbusiness.login');
Route::View('business', 'web.business')->name('business.login');
Route::View('select', 'web.select')->name('select.login');
Route::View('company', 'web.company')->name('company.login');
Route::View('bookkeeper/login', 'web.bookkeeperLogin')->name('bookkeeper.login');



Route::view('not-allowed/', 'not-allowed');


Route::middleware('set_db')->group(function () {

    Route::post('/set-theme', 'AppController@setTheme')->name('set-theme');




    // Route::get('/', function () { return redirect('dashboard/index'); });

    // ****************************** Authentication ********************************
    // Route::get('/', function () { return redirect('authentication/login'); });
    Route::get('login', function () {
        return redirect('authentication/login');
    });
    Route::get('authentication', function () {
        return redirect('authentication/login');
    });
    Route::any('authentication/authenticate', 'AuthenticationController@authenticate');
    Route::get('authentication/login', 'AuthenticationController@login')->name('authentication.login');
    Route::get('authentication/register', 'AuthenticationController@register')->name('authentication.register');
    Route::get('authentication/lockscreen', 'AuthenticationController@lockscreen')->name('authentication.lockscreen');
    Route::get('authentication/forgot', 'AuthenticationController@forgot')->name('authentication.forgot');
    Route::get('authentication/page404', 'AuthenticationController@page404')->name('authentication.page404');
    Route::get('authentication/page500', 'AuthenticationController@page500')->name('authentication.page500');
    Route::get('authentication/offline', 'AuthenticationController@offline')->name('authentication.offline');
    Route::get('authentication/logout', 'AuthenticationController@logout');
    Route::get('Page/404', 'AuthenticationController@page404notFound');

    Route::get('auth', function () {
        echo "a";
    });

    // *******************************************************************************************
    // ******************************** Admin Module *****************************************
    // *******************************************************************************************
    Route::middleware('check_route')->group(function () {

        // User
        Route::get('Admin/Users/List', 'System\UserController@index')->name('Admin/Users/List');
        Route::get('Admin/Users/Create/{id?}/{profile?}', 'System\UserController@form');
        Route::post('Admin/Users/Add', 'System\UserController@save');
        Route::post('Admin/UserProfile/Update/{profile?}', 'System\UserController@user_prof');
        Route::get('Admin/Users/Remove/{id}', 'System\UserController@hide');
        Route::post('Admin/Users/aj_verifyEmail', 'System\UserController@email_verification');
        Route::get('Admin/RouteManagement', 'System\RoleController@routeManagament');
        Route::post('Child/Route/Fetch', 'System\RoleController@childRouteFetch');
        Route::get('Admin/ChildRouteManagement/{id}', 'System\RoleController@childRouteManagament');
        Route::post('Admin/ChildRouteManagement', 'System\RoleController@routestore');



        //system
        Route::get('download/{id}', 'System\FileController@downloadFile');
        Route::get('display/{id}', 'System\FileController@displayFile');
        Route::post('delete/{id?}', 'System\FileController@deleteFile');


        // Role
        Route::get('Admin/RoleManagement/List', 'System\RoleController@index')->name('role_list');
        Route::get('Admin/RoleManagement/Create', 'System\RoleController@form');
        Route::post('Admin/RoleManagement/Add', 'System\RoleController@save');
        Route::post('Admin/RoleManagement/aj_moduleChild', 'System\RoleController@moduleChild');
        Route::post('Admin/RoleManagement/aj_moduleChildEdit', 'System\RoleController@moduleChildEdit');
        Route::get('Admin/RoleManagement/roleRemove/{id}', 'System\RoleController@hide');
        Route::get('Admin/RoleManagement/RoleEdit/{id?}', 'System\RoleController@edit')->name('Admin/RoleManagement/RoleEdit');
        Route::post('Admin/RoleManagement/RoleUpdates', 'System\RoleController@update');
        Route::post('ModuleGet', 'System\RoleController@ModuleGet');



        //Form Options
        Route::get('Admin/FormOptions/List', 'System\FormOptionsController@index');
        Route::get('Admin/FormOptions/Create/{id?}', 'System\FormOptionsController@form');
        Route::Post('Admin/FormOptions/Add', 'System\FormOptionsController@save');
        Route::get('Admin/FormOptions/removeOption/{id}', 'System\FormOptionsController@option_hide');

        //Company
        Route::get('Admin/Company/List/{id?}', 'System\CompanyController@company_index');
        Route::get('Admin/Company/Create/{id?}', 'System\CompanyController@company_form');
        Route::post('Admin/Company/Add', 'System\CompanyController@company_save');
        Route::post('companyAttachDelete', 'System\CompanyController@logoRemove');

        //Warehouse
        Route::get('Admin/Warehouse/List', 'System\CompanyController@warehouse_index');
        Route::get('Admin/Warehouse/Create/{id?}', 'System\CompanyController@warehouse_form');
        Route::post('Admin/Warehouse/Add', 'System\CompanyController@warehouse_save');

        //Taxes
        Route::get('Admin/Taxes/List', 'System\CompanyController@taxes_index')->name('Admin/Taxes/List');
        Route::get('Admin/Taxes/Create/{id?}', 'System\CompanyController@taxes_form');
        Route::post('Admin/Taxes/Add', 'System\CompanyController@taxes_save');

        //Discounts
        Route::get('Admin/Discounts/List', 'System\CompanyController@discount_index')->name('Admin/Discounts/List');
        Route::get('Admin/Discounts/Create/{id?}', 'System\CompanyController@discounts_form');
        Route::post('Admin/Discounts/Add', 'System\CompanyController@discounts_save');

        //Terms & Condition
        Route::get('Admin/Term/List', 'System\CompanyController@terms_index')->name('Admin/Term/List');
        Route::get('Admin/Term/List/Create/{id?}', 'System\CompanyController@terms_form');
        Route::post('Admin/Term/List/Add', 'System\CompanyController@terms_save');

        //Modules
        Route::get('Admin/Modules/List', 'System\ModulesController@index')->name('Admin/Modules/List');
        Route::get('Admin/Modules/View/{id?}', 'System\ModulesController@viewData')->name('Admin/Modules/View/');
        Route::post('Admin/Modules/Save/', 'System\ModulesController@saveData')->name('Admin/Modules/Save/');

        //Departments
        Route::get('Admin/Department/List', 'System\CompanyController@depart_index');
        Route::get('Admin/Department/Create/{id?}', 'System\CompanyController@depart_form');
        Route::post('Admin/Department/Add', 'System\CompanyController@depart_save');
        Route::post('Admin/Department/Edit', 'System\CompanyController@depart_edit');
        Route::get('Admin/Department/depart_hide/{id}', 'System\CompanyController@depart_remove');

        // Imap Setting
        Route::get('Admin/Smtp/Setting/{id?}', 'System\CompanyController@smtpView');
        Route::post('Admin/Smtp/Setting', 'System\CompanyController@smtpstore')->name('smtp.setting');
        Route::get('Admin/Smtp/Setting/Delete/{credential_id?}', 'System\CompanyController@smtpdelete')->name('smtp.setting.delete');
        Route::get('Admin/Smtp/Setting/Active/{id?}', 'System\CompanyController@smtpActive')->name('smtp.active');
        Route::get('Admin/Smtp/Setting/DeActive/{id?}', 'System\CompanyController@smtpDeActive');
        Route::get('Admin/Smtp/Test/{id?}', 'Ticket\EmailController@imapTest');

        // Mail Setting
        Route::get('Admin/Mail/Setting/{id?}', 'System\CompanyController@mailview');
        Route::post('Admin/Mail/Setting', 'System\CompanyController@mailstore')->name('mail.setting');
        Route::get('Admin/Mail/Setting/Delete/{id}', 'System\CompanyController@maildelete');
        Route::get('Admin/Mail/Setting/Active/{id?}', 'System\CompanyController@MailActive')->name('Mail.active');
        Route::get('Admin/Mail/Setting/DeActive/{id?}', 'System\CompanyController@MailDeActive');
        Route::get('Admin/Smtp/Test/{id?}', 'Ticket\EmailController@imapTest');
        Route::get('Admin/Mail/Test/{id?}', 'Ticket\EmailController@mailTest');

        // Currencies Setting
        Route::get('Admin/Currencies/List/{id?}', 'System\CurrencyController@currencyList');
        Route::post('Admin/Currencies/Store', 'System\CurrencyController@currencyStore')->name('Admin/Currencies/Store');
        Route::get('Admin/Currencies/Delete/{id}', 'System\CurrencyController@currencyDelete');







        // Route::get('/home', 'HomeController@index')->name('home');

        // ******************************** App Controllers (General app infomration required in entire app)*****************************************
        Route::post('getCurrencyRate', 'AppController@getCurrencyRate')->name('getCurrencyRate');
        Route::post('getTaxRates', 'AppController@getTaxRates')->name('getTaxRates');
        Route::post('coaSearch', 'AppController@coaSearch')->name('coaSearch');
        Route::post('customerSearch', 'AppController@customerSearch')->name('customerSearch');
        Route::post('leadSearch', 'AppController@leadSearch')->name('leadSearch');
        Route::post('leadCustmerSearch', 'AppController@leadCstSearch')->name('leadCstSearch');
        Route::post('projectSearch', 'AppController@projectSearch')->name('projectSearch');
        Route::post('itemSearch', 'AppController@itemSearch')->name('itemSearch');
        Route::post('rawItemSearch', 'AppController@rawItemSearch')->name('rawItemSearch');
        Route::post('getItemDetail', 'AppController@getItemDetail')->name('getItemDetail');
        Route::post('getInquiries', 'AppController@getInquiries')->name('getInquiries');
        Route::post('getQuotations', 'AppController@getQuotations')->name('getQuotations');
        Route::post('accountSearch', 'AppController@coaSearch')->name('accountSearch');
        Route::post('productSearch', 'AppController@productSearch')->name('productSearch');
        Route::post('vendorSearch', 'AppController@vendorSearch')->name('vendorSearch');
        Route::post('vendorCoaSearch', 'AppController@vendorCoaSearch')->name('vendorCoaSearch');
        Route::post('contactsSearch', 'AppController@contactsSearch')->name('contactsSearch');
        Route::post('userSearch', 'AppController@userSearch')->name('userSearch');
        Route::post('transactionAccountSearch', 'AppController@coaSearch')->name('transactionAccountSearch');
        Route::post('locationSearch', 'AppController@getlocation')->name('locationSearch');
        Route::post('batchSearch', 'AppController@checkBatch');
        Route::post('SaleItemSearch', 'AppController@SaleItemSearch');
        Route::post('customerDetailSearch', 'AppController@getCustomerDetail');

        //Route::post('employeeSearch','AppController@employeeSearch')->name('employeeSearch');





        // *******************************************************************************************
        // ******************************** Accounting Module ****************************************
        // *******************************************************************************************

        Route::get('Accounts/CoaList', 'Accounts\CoaController@list')->name('Accounts/CoaList');
        Route::get('Accounts/Coa', 'Accounts\CoaController@index');
        Route::get('Accounts/Coa{coaId}', 'Accounts\CoaController@index');
        Route::get('Accounts/Coa{Id}', 'Accounts\CoaController@index');
        Route::post('Accounts/CoaSave', 'Accounts\CoaController@save');
        Route::post('Accounts/CoaListEdit', 'Accounts\CoaController@fetchAccountInfo')->name('Accounts/CoaListEdit');
        Route::post('Accounts/EditParentAccount', 'Accounts\CoaController@editParent');
        Route::post('Accounts/DelChildAccount', 'Accounts\CoaController@deleteAccount');

        //Journal Voucher
        Route::get('Accounts/JournalVoucher/{type}/{id?}', 'Accounts\CashVoucherController@jIndex')->name('JournalVoucher');
        Route::post('Accounts/journalVoucherSave', 'Accounts\CashVoucherController@journalVoucherSave');

        //Cash Voucher
        // Route::get('Accounts/CashVoucher', 'Accounts\CashVoucherController@index')->name('CashVoucher');
        Route::get('Accounts/CashVoucher/List/{type?}/{id?}', 'Accounts\CashVoucherController@list');
        Route::post('getCashTransactionList', 'Accounts\CashVoucherController@getCashTransactionList');

        //Route::get('Accounts/PaymentVoucher/List/{type}/{id?}', 'Accounts\CashVoucherController@paymentList')->name('CashVoucher');
        Route::get('Accounts/CashVoucher/{type}/{id?}', 'Accounts\CashVoucherController@index')->name('CashVoucher');
        Route::post('Accounts/cashVoucherSave', 'Accounts\CashVoucherController@cashVoucherSave')->name('cashVoucherSave');

        Route::post('accounts', 'Accounts\CashVoucherController@accounts');
        Route::post('get_closing_balance', 'Accounts\CashVoucherController@getBalance');




        //Expense
        Route::get('Accounts/ExpenseList/{source}', 'Accounts\AccountController@list');
        Route::get('Accounts/Expense/{source}/{id?}', 'Accounts\AccountController@view');
        Route::post('Accounts/ExpenseSave/{source}', 'Accounts\AccountController@save');


        //Payment Received
        Route::get('Account/PaymentReceived', 'Account\PaymentController@paymentList');

        //Received From Customer
        Route::get('Account/ReceivedFromCustomer/List/{type?}', 'Accounts\ReceivedFromCustomerController@list');
        Route::get('Account/ReceivedFromCustomer/Create/{type}/{id?}', 'Accounts\ReceivedFromCustomerController@receivedFromCustomer');
        Route::post('Account/ReceivedAmountSave', 'Accounts\ReceivedFromCustomerController@receivedAmountSave');
        Route::post('customerInvoices', 'Accounts\ReceivedFromCustomerController@customerInvoices');
        Route::post('received_in', 'Accounts\ReceivedFromCustomerController@receivedIn');
        Route::post('getLedgerBalance', 'Accounts\ReceivedFromCustomerController@getLedgerBalance');

        //Vendor Payment
        Route::get('Accounts/VendorPayment/List', 'Accounts\VendorPaymentController@index');
        Route::get('Account/VendorPayment/Create/{id?}', 'Accounts\VendorPaymentController@form');
        Route::post('vendorInvoices', 'Accounts\VendorPaymentController@vendorInvoices');
        Route::post('Account/VendorPaymentSave', 'Accounts\VendorPaymentController@save');

        //Daily Recovery
        Route::get('Accounts/DailyRecorvery/List', 'Accounts\DailyRecoveryController@index');
        Route::get('Accounts/DailyRecorvery/Create/{id?}', 'Accounts\DailyRecoveryController@form');
        Route::post('Accounts/DailyRecorvery/Add', 'Accounts\DailyRecoveryController@save');
        Route::post('DailyRecovery/Report', 'Accounts\DailyRecoveryController@report');



        //Adjustment
        Route::get('Accounts/Adjustment/List/{type?}', 'Accounts\ReceivedFromCustomerController@list');

        //Opening Balance
        Route::get('Accounts/OpeningBalance/List', 'Accounts\OpeningBalanceController@form');
        Route::post('Accounts/OpeningBalance/Add', 'Accounts\OpeningBalanceController@save');
        Route::post('Accounts/getOpeningBalance', 'Accounts\OpeningBalanceController@getOpeningAccounts');

        //TransactionList
        Route::get('Accounts/TransactionList', 'Accounts\TransactionListController@list');
        Route::post('getTransactionList', 'Accounts\TransactionListController@transactionList');

        //Post Transaction
        Route::get('Accounts/PostTransaction/List', 'Accounts\PostTransactionController@list');
        Route::post('getUnPostTransactionList', 'Accounts\PostTransactionController@unPostTransactionList');
        Route::post('updatePostStatus', 'Accounts\PostTransactionController@post');


        // *******************************************************************************************
        // ******************************** Inventory Module *****************************************
        // *******************************************************************************************

        //Product
        Route::get('Inventory/Product/List', 'Inventory\InventoryController@product_index')->name('Inventory/Product/List');
        Route::get('Inventory/Product/Create/{id?}', 'Inventory\InventoryController@product_form');
        Route::get('Inventory/Product/Views/{id}', 'Inventory\InventoryController@product_view');
        Route::post('Inventory/Product/Add', 'Inventory\InventoryController@product_save');
        Route::post('unitsSearch', 'Inventory\InventoryController@units');
        Route::post('productAttachDelete', 'Inventory\InventoryController@attach_remove');

        // Purchase Requistions
        Route::get('Inventory/PurchaseRequistion/List', 'Inventory\PurchaseRequistionController@index');
        Route::get('Inventory/PurchaseRequistion/Create/{id?}', 'Inventory\PurchaseRequistionController@form');
        Route::get('Inventory/PurchaseRequistion/Views/{id}', 'Inventory\PurchaseRequistionController@view');
        Route::post('Inventory/PurchaseRequistion/Add', 'Inventory\PurchaseRequistionController@save');
        Route::post('attachmentDelete', 'Inventory\PurchaseRequistionController@attach_remove');

        //GRN
        Route::get('Inventory/GoodReceived/List', 'Inventory\GoodReceivedController@index')->name('Inventory/GoodReceived/List');
        Route::get('Inventory/GoodReceived/Create/{id?}', 'Inventory\GoodReceivedController@form')->name('Inventory/GoodReceived');
        Route::post('Inventory/GoodReceived/Add', 'Inventory\GoodReceivedController@save');
        Route::get('Inventory/GoodReceived/Views/{id}', 'Inventory\GoodReceivedController@view');
        Route::post('Inventory/aj_igpDetail', 'Inventory\GoodReceivedController@igp_record');


        //GRN-WIP-outsourced
        Route::get('Inventory/GRN_WIP/List', 'Inventory\GrnWipController@index');
        Route::get('Inventory/GRN_WIP/Create/{id?}', 'Inventory\GrnWipController@form');
        Route::post('Inventory/GRN_WIP/Add', 'Inventory\GrnWipController@save');
        Route::post('Inventory/aj_batchDetail', 'Inventory\GrnWipController@batch_record');
        Route::post('Inventory/aj_ReciepeDetail', 'Inventory\GrnWipController@reciepe_record');
        Route::post('reciepeSearch', 'Inventory\GrnWipController@reciepeSearch');
        Route::get('Inventory/GRN_WIP/Views/{id}', 'Inventory\GrnWipController@view');
        Route::get('Inventory/GRN_WIP/Print/{id}', 'Report\InventoryReportsController@grn_wip_print');


        //Purchase Return
        Route::get('Inventory/PurchaseReturnList', 'Inventory\PurchaseReturnController@pur_return_list');
        Route::get('Inventory/PurchaseReturn/Create/{id?}', 'Inventory\PurchaseReturnController@form');
        Route::post('Inventory/VendorGrDetail', 'Inventory\PurchaseReturnController@grn_record');
        Route::post('Inventory/purInvDetail', 'Inventory\PurchaseReturnController@pur_inv_detail');
        Route::post('Inventory/PurchaseReturn/Add', 'Inventory\PurchaseReturnController@save');

        //GIN sales
        Route::get('Inventory/GinSalesList', 'Inventory\GinController@gn_sal_list')->name('Inventory/GinSalesList');
        Route::post('Inventory/CustomerInvoice', 'Inventory\GinController@invoiceNumber');
        Route::get('Inventory/GinSales/{id?}', 'Inventory\GinController@gn_sal')->name('Inventory/GinSales');
        Route::post('Inventory/GinSalesSave', 'Inventory\GinController@gn_sal_save')->name('Inventory/GinSalesSave');

        //GIN-WIP
        Route::get('Inventory/GIN_WIP/List', 'Inventory\GIN_WIPController@index');
        Route::get('Inventory/GIN_WIP/Create/{id?}', 'Inventory\GIN_WIPController@form');
        Route::post('Inventory/GIN_WIP/Add', 'Inventory\GIN_WIPController@save');
        Route::post('Inventory/mirDetail', 'Inventory\GIN_WIPController@mirNumber');

        //GIN-WIP Return
        Route::get('Inventory/GIN_WIP_Return/List', 'Inventory\GIN_WIP_ReturnController@index');
        Route::get('Inventory/GIN_WIP_Return/Create/{id?}', 'Inventory\GIN_WIP_ReturnController@form');
        Route::post('Inventory/GIN_WIP/Detail', 'Inventory\GIN_WIP_ReturnController@getGin_wipDetail');
        Route::post('Inventory/GIN_WIP_Return/Add', 'Inventory\GIN_WIP_ReturnController@save');
        Route::post('getWipDetail', 'Inventory\GIN_WIP_ReturnController@getWipStock');

        //product type
        Route::get('Inventory/ProductType/List', 'Inventory\ProductTypeController@index')->name('Inventory/ProductType/List');
        Route::get('Inventory/ProductType/remove/{id}', 'Inventory\ProductTypeController@hide');
        Route::get('Inventory/editProductType/{id}', 'Inventory\ProductTypeController@edit');
        Route::get('Inventory/productType/Create', 'Inventory\ProductTypeController@save');
        Route::post('Inventory/productType/Add', 'Inventory\ProductTypeController@save');

        //Brands
        Route::get('Inventory/Brands/List', 'Inventory\InventoryController@br_list')->name('Inventory/Brands/List');
        Route::post('Inventory/Brands/Add', 'Inventory\InventoryController@br_save');
        Route::post('Inventory/Brands/Edit', 'Inventory\InventoryController@br_edit');
        Route::get('Inventory/Brands/brandRemove/{id}', 'Inventory\InventoryController@br_remove');

        //internal transfer
        Route::get('Inventory/IT', 'Inventory\InternalTransferController@inter_trans_List');
        Route::get('Inventory/InternalTransfer/{id?}', 'Inventory\InternalTransferController@inter_trans');
        Route::post('Inventory/InternalTransferSave', 'Inventory\InternalTransferController@inter_trans_save');

        //product category
        Route::get('Inventory/ProductCategoryList', 'Inventory\ProductCategoryController@prod_cate_List')->name('Inventory/ProductCategoryList');
        Route::get('Inventory/removeProductCategory/{id}', 'Inventory\ProductCategoryController@prod_cate_remove');
        Route::get('Inventory/editProductCategory/{id}', 'Inventory\ProductCategoryController@prod_cate_edit');
        Route::get('Inventory/productCategory', 'Inventory\ProductCategoryController@prod_cate_save');
        Route::post('Inventory/productCategory', 'Inventory\ProductCategoryController@prod_cate_save');

        //Sales Return
        Route::get('Inventory/SalesReturn/List', 'Inventory\SalesReturnController@index')->name('Inventory/SalesReturnList');
        Route::get('Inventory/SalesReturn/Create/{id?}', 'Inventory\SalesReturnController@form')->name('Inventory/SalesReturn');
        Route::post('Inventory/DoDetail', 'Inventory\SalesReturnController@getDoDetail');
        Route::post('Inventory/SalesReturn/Save', 'Inventory\SalesReturnController@save');

        //Units
        Route::get('Inventory/Unit/List', 'Inventory\UnitsController@index')->name('Inventory/Unit/List');
        Route::get('Inventory/Unit/Create/{id?}', 'Inventory\UnitsController@form');
        Route::post('Inventory/Unit/Add', 'Inventory\UnitsController@save');

        //Inward Gate Pass
        Route::get('Inventory/IGP/List', 'Inventory\IGNController@index');
        Route::get('Inventory/IGP/Create/{id?}', 'Inventory\IGNController@form');
        Route::get('Inventory/IGP/Views/{id?}', 'Inventory\IGNController@view');
        Route::post('Inventory/IGP/Add', 'Inventory\IGNController@save');
        Route::post('Inventory/aj_PoDetail', 'Inventory\IGNController@po_record');

        //DO
        Route::get('Inventory/Do', 'Inventory\DoController@index')->name('Inventory/Do');
        Route::get('Inventory/Do/Create/{id?}', 'Inventory\DoController@form');
        Route::post('Inventory/Do/Add', 'Inventory\DoController@save');
        Route::post('Inventory/Do/saleInvoiceDetail', 'Inventory\DoController@getInvoiceDetail');
        // Route::post('Inventory/Do/saleInvoiceDetail','Inventory\DoController@getInvoiceDetail');
        Route::post('check/DeliveryStatus', 'Inventory\DoController@report');
        //Route::post('Inventory/Do/List','Inventory\DoController@listData');
        Route::post('Inventory/Do/List', 'Inventory\DoController@listData');

        //Opening Balance
        Route::get('Inventory/OpeningBalance/List', 'Inventory\OpeningBalanceController@form');
        Route::post('Inventory/OpeningBalance/Add', 'Inventory\OpeningBalanceController@save');
        Route::post('itemBalanceSearch', 'Inventory\OpeningBalanceController@openingBalance');


        // *******************************************************************************************
        // ******************************** Production Module *****************************************
        // *******************************************************************************************

        //Material Issue Request
        Route::get('Production/MIR/List', 'Production\MIRController@index')->name('Production/MIR/List');
        Route::get('Production/MIR/Create/{id?}', 'Production\MIRController@form');
        Route::get('Production/MIR/Views/{id}', 'Production\MIRController@view');
        Route::post('Production/MIR/Add', 'Production\MIRController@save');

        //Reciepe
        Route::get('Production/Reciepe/List', 'Production\ReciepeController@index')->name('Production/Reciepe/List');
        Route::get('Production/Reciepe/Create/{id?}', 'Production\ReciepeController@form');
        Route::post('Production/Reciepe/Add', 'Production\ReciepeController@save');


        // *******************************************************************************************
        // ******************************** Project Module *****************************************
        // *******************************************************************************************

        //Project Managment
        Route::get('Project/ProjectManagment/List', 'Project\ProjectManagementController@index')->name('Project/ProjectManagment/List');
        Route::get('Project/ProjectManagment/Create/{id?}', 'Project\ProjectManagementController@form');
        Route::post('Project/ProjectManagment/Add', 'Project\ProjectManagementController@save');
        Route::get('Project/ProjectManagment/View/{id}', 'Project\ProjectManagementController@view')->name('Project.Managment');
        Route::post('Project/TeamSave', 'Project\ProjectManagementController@add_team');
        Route::post('Project/AttachmentSave', 'Project\ProjectManagementController@AttachmentSave');
        Route::get('Project/AttachmentDelete/{id}', 'Project\ProjectManagementController@AttachmentDelete');
        // save team

        //Secheduler
        Route::get('Project/Secheduler/View', 'Project\SechedulerController@view');
        Route::post('Project/Secheduler/addSechduler', 'Project\SechedulerController@addSechduler');
        Route::post('Project/Secheduler/removeSechduler', 'Project\SechedulerController@removeSechduler');
        Route::post('Project/Secheduler/GetbyDate', 'Project\SechedulerController@getSechduleByDate');

        // *******************************************************************************************
        // ******************************** Purchase Module *****************************************
        // *******************************************************************************************

        Route::get('Purchase/PurchaseOrder/List', 'Purchase\PurchaseOrderController@index');
        Route::get('Purchase/PurchaseOrder/Create/{id?}', 'Purchase\PurchaseOrderController@form');
        Route::get('Purchase/PurchaseOrder/View/{id}', 'Purchase\PurchaseOrderController@view');
        Route::post('Purchase/PrDetail', 'Purchase\PurchaseOrderController@PrDetail');
        Route::post('Purchase/PurchaseOrder/Add', 'Purchase\PurchaseOrderController@save');
        Route::post('prAttachmentDelete', 'Purchase\PurchaseOrderController@attach_remove');
        Route::delete('/purchaseRemove/{id}', 'Purchase\PurchaseOrderController@destroy')->name('purchaseOrder.destroy');



        //Purchase Invoice
        Route::get('Purchase/PurchaseInvoice/List', 'Purchase\PurchaseInvoiceController@index');
        Route::get('Purchase/PurchaseInvoice/Create/{id?}', 'Purchase\PurchaseInvoiceController@form');
        Route::get('Purchase/PurchaseInvoice/Listpdf/{id?}', 'Purchase\PurchaseInvoiceController@pdf');
        Route::post('grn_detail', 'Purchase\PurchaseInvoiceController@grn_detail');
        Route::post('Purchase/PurchaseInvoice/Add', 'Purchase\PurchaseInvoiceController@save');



        //Purchase Expenses
        Route::get('Purchase/PurchaseExpenses/List', 'Purchase\PurchaseExpensesController@index')->name('PurchaseExpList');
        Route::get('Purchase/PurchaseExpenses', 'Purchase\PurchaseExpensesController@form');
        Route::post('Purchase/PurchaseExpenses', 'Purchase\PurchaseExpensesController@saveExpenses');
        Route::get('Purchase/PurchaseExpensesView/{id}', 'Purchase\PurchaseExpensesController@viewExpenses')->name('PurchaseExpenses');
        Route::get('Purchase/PurchaseExpenses/bookInvoice/{id}/{expId?}', 'Purchase\PurchaseExpensesController@bookExpenses')->name('bookExpenses');
        Route::post('Purchase/PurchaseExpenses/saveInvoice', 'Purchase\PurchaseExpensesController@saveInvoice');


        // Cost deployment
        Route::post('Purchase/PurchaseExpenses/costDeploymentView', 'Purchase\PurchaseExpensesController@applyCostsView');
        Route::post('Purchase/PurchaseExpenses/InventoryCost', 'Purchase\PurchaseExpensesController@closePurchaseOrder');




        /*





Route::post('Purchase/PurchaseExpenses/savedetails','Purchase\PurchaseExpensesController@saveDetail');
Route::get('Purchase/PurchaseExpenses/details/edit/{id}','Purchase\PurchaseExpensesController@edit');
Route::post('Purchase/PurchaseExpenses/details/update/{id}','Purchase\PurchaseExpensesController@updateDetail');
*/


        // *******************************************************************************************
        // ******************************** CRM Module *****************************************
        // *******************************************************************************************


        // Leads
        Route::get('Crm/Leads/List/{id?}', 'Crm\CrmController@index')->name('Crm/Leads/List/');
        Route::get('Crm/Leads/Create/{id?}', 'Crm\CrmController@form')->name('Crm/Leads/Create/');
        Route::get('Crm/Leads/Views/{id?}', 'Crm\CrmController@view')->name('Crm/Leads/Views/');
        Route::post('Crm/Leads/Add', 'Crm\CrmController@save')->name('Crm/Leads/Add');
        Route::post('Crm/Leads/import', 'Crm\CrmController@import')->name('leads.import');


        // Routes for Blog section

        // Route::get('Blog//List/{id?}','Blog\BlogController@index')->name('Blog/List/');


        // Customers
        Route::get('Crm/Customers/List/{id?}', 'Crm\CustomerController@index')->name('Crm/Customers/List/');
        Route::get('Crm/Customers/Create/{id?}', 'Crm\CustomerController@form')->name('Crm/Customers/Create/');
        Route::get('Crm/Customers/Views/{id?}', 'Crm\CustomerController@view')->name('Crm/Customers/Views/');
        Route::post('Crm/Customers/Add', 'Crm\CustomerController@save')->name('Crm/Customers/Add');
        Route::post('Crm/Customers/Update/{id}', 'Crm\CustomerController@update')->name('Crm/Customers/Update');

        Route::get('Crm/Customers/edit/{id?}', 'Crm\CustomerController@form')->name('Crm/Customers/Create/');

        Route::get('Crm/CutomerCategory/List', 'Crm\CustomerCategoryController@index')->name('Crm/CutomerCategory/List');
        Route::get('Crm/CutomerCategory/Create', 'Crm\CustomerCategoryController@customerCategory');
        Route::post('Crm/CutomerCategory/Add', 'Crm\CustomerCategoryController@customerCategory');
        Route::get('Crm/CustomerCategory/Remove/{id}', 'Crm\CustomerCategoryController@removeCategory');
        Route::get('Crm/CustomerCategory/Edit/{id}', 'Crm\CustomerCategoryController@editCategory');
        Route::post('Crm/Customers/import', 'Crm\CustomerController@import')->name('customers.import');

        //contact
        Route::get('Crm/Contacts/List', 'Crm\ContactsController@index')->name('Crm/Contacts/List');
        Route::get('Crm/Contacts/Views/{id}', 'Crm\ContactsController@view');
        Route::get('Crm/Contacts/Create/{id?}/{relatedTo?}/{refId?}', 'Crm\ContactsController@form')->name('crm.contacts.create');
        //Route::get('Crm/Contacts/Create/{id?}/{type?}', 'Crm\ContactsController@form')->name('crm.contacts.create');

        Route::post('Crm/Contacts/Add', 'Crm\ContactsController@save');
        // Route::post('Crm/Contacts/delete/{id?}','Crm\ContactsController@delete');


        //Tasks
        Route::get('Crm/TasksList', 'Crm\TasksController@tasksList')->name('Crm/TasksList');
        Route::post('Crm/getTasksList', 'Crm\TasksController@getTasksList');
        Route::get('Crm/Tasks/{id?}/{relatedTo?}/{refId?}', 'Crm\TasksController@tasks');
        Route::post('Crm/TasksSave', 'Crm\TasksController@tasksSave');

        //Opportunities
        Route::get('Crm/Opportunities/List', 'Crm\OpportunitiesController@index')->name('Crm/Opportunities/List');
        Route::get('Crm/Opportunities/Create/{id?}', 'Crm\OpportunitiesController@form');
        Route::post('Crm/Opportunities/Add', 'Crm\OpportunitiesController@save');
        Route::get('Crm/Opportunities/Remove/{id?}', 'Crm\OpportunitiesController@remove');
        Route::post('opportunitiesSearch', 'Crm\OpportunitiesController@searchOpportunities');

        //Notes
        Route::get('Crm/Notes/List', 'Crm\NotesController@index')->name('Crm/Notes/List');
        Route::post('Crm/getNotesList', 'Crm\NotesController@getNotesList');
        Route::get('Crm/Notes/Create/{id?}/{relatedTo?}/{refId?}', 'Crm\NotesController@form');
        Route::get('Crm/Notes/Views/{id}', 'Crm\NotesController@View');
        Route::post('Crm/Notes/Add', 'Crm\NotesController@save');

        //Calls
        Route::get('Crm/Calls/List', 'Crm\CallsController@index')->name('Crm/Calls/List');
        Route::post('Crm/getCallsList', 'Crm\CallsController@getCallsList');
        Route::get('Crm/Calls/Create/{id?}/{relatedTo?}/{refId?}', 'Crm\CallsController@form');
        Route::post('Crm/Calls/Add', 'Crm\CallsController@save');

        //Calender View
        Route::get('Crm/Calender/View', 'Crm\CalendarController@calendarView');
        Route::get('Crm/CalendarEvents', 'Crm\CalendarController@getEvent')->name('Crm/Calendar');

        //general calender
        Route::get('App/calender', 'AppController@calendar');


        // EMail Emplates and compaigns
        // EMail Emplates and compaigns
        Route::get('Crm/EmailTemplates', [EmailTemplateController::class, 'templateList']);
        Route::get('Crm/EmailTemplates/createTemplate/form', [EmailTemplateController::class, 'templateform']);
        Route::post('Crm/EmailTemplates/createTemplate', [EmailTemplateController::class, 'createTemplate']);
        Route::get('Crm/EmailTemplates/edit/{id}', [EmailTemplateController::class, 'editTemplate']);
        Route::post('Crm/EmailTemplates/update/{id}', [EmailTemplateController::class, 'updateTemplate']);
        Route::get('Crm/EmailTemplates/delete/{id}', [EmailTemplateController::class, 'deleteTemplate']);



        // Route::get('email-templates',[App\Http\Controllers\EmailTemplate\EmailTemplateController::class, 'email_temp']);
        Route::get('/wpa_blogs', 'EmailTemplateControllerr@Event_3');
        Route::get('/wpa_blogs', 'EmailTemplateControllerr@Event_3');


        Route::get('Crm/EmailCampaign', [EmailCampaignController::class, 'campList'])->name('crm.campList');
        Route::get('Crm/EmailCampaign/createcampaign/form', [EmailCampaignController::class, 'campaignform']);
        Route::get('Crm/EmailCampaign/categories', [EmailCampaignController::class, 'categoryData']);
        Route::post('Crm/EmailCampaign/createCampaign', [EmailCampaignController::class, 'createCampaign']);
        Route::get('Crm/EmailCampaign/edit/{id}', [EmailCampaignController::class, 'editcamp_email']);
        Route::post('Crm/EmailCampaign/update', [EmailCampaignController::class, 'updatecamp_email']);
        Route::get('Crm/EmailCampaign/delete/{id}', [EmailCampaignController::class, 'deletecamp_email']);
        Route::post('Crm/EmailCampaign/Saveemail', [EmailCampaignController::class, 'saveEmail']);
        Route::get('Crm/EmailCampaign/SendEmail', [EmailCampaignController::class, 'Emailsend']);
        Route::get('Crm/EmailCampaign/searchCampaigns', [EmailCampaignController::class, 'searchCampaigns']);
        Route::get('Crm/EmailCampaign/email/data', [EmailCampaignController::class, 'refreshInfo']);
        Route::get('Crm/EmailCampaign/preview/template', [EmailCampaignController::class, 'preViewTemplate']);
        Route::post('Crm/EmailCampaign/send/emails', [EmailCampaignController::class, 'sendEmailsNow']);

        // *******************************************************************************************
        // ******************************** HCM Module *****************************************
        // *******************************************************************************************
        //Employe Form
        Route::get('HCM/EmployeeForm/List', 'Hcm\EmployeeFormController@index');
        Route::get('HCM/EmployeeForm/Create/{id?}', 'Hcm\EmployeeFormController@form');
        Route::post('HCM/EmployeeForm/Add', 'Hcm\EmployeeFormController@save');
        Route::get('HCM/EmployeeData/View/{id}', 'Hcm\EmployeeFormController@View');


        Route::get('HCM/EmployeeForm/getEvents', 'Hcm\EmployeeFormController@getEvent');


        Route::get('HCM/Designation/{id?}', 'Hcm\EmployeeFormController@designation');
        Route::post('HCM/Designation', 'Hcm\EmployeeFormController@postdesignation');
        Route::get('HCM/Designation/Delete/{id}', 'Hcm\EmployeeFormController@deletedesignation');




        // *******************************************************************************************
        // ******************************** Sales Module *****************************************
        // *******************************************************************************************

        // Inquiry
        Route::get('Sales/Inquiry/List', 'Sales\InquiryController@index')->name('Sales/Inquiry/List');
        Route::get('Sales/Inquiry/Create/{id?}', 'Sales\InquiryController@form');
        Route::post('Sales/Inquiry/Add', 'Sales\InquiryController@save');

        // Quotation
        Route::get('Sales/Quotation/List', 'Sales\QuotationController@index')->name('Sales/Quotation/List');
        Route::get('Sales/Quotation/Create/{id?}', 'Sales\QuotationController@form');
        Route::post('Sales/Quotation/Add', 'Sales\QuotationController@save');

        // sale Order
        Route::get('Sales/SaleOrder/List', 'Sales\SaleOrderController@index')->name('Sales/SaleOrder/List');
        Route::get('Sales/SaleOrder/Create/{id?}', 'Sales\SaleOrderController@form');
        Route::post('Sales/SaleOrder/Add', 'Sales\SaleOrderController@save');
        Route::post('Sales/SaleOrder/GetSaleOrder', 'Sales\SaleOrderController@getSaleOrder');



        //Services
        Route::get('Sales/Services/List', 'Sales\ServicesController@index'); //->name('Sales/Services/List');
        Route::get('Sales/Services/Create', 'Sales\ServicesController@servicesSave');
        Route::post('Sales/Services/Add', 'Sales\ServicesController@servicesSave');
        Route::get('Sales/Services/remove/{id}', 'Sales\ServicesController@removeService');
        Route::get('Sales/Services/Edit/{id}', 'Sales\ServicesController@editService');

        // Sale Invoice
        Route::get('Sales/Invoice/List/{type?}', 'Sales\InvoiceController@index')->name('Sales/Invoice/List');
        Route::get('Sales/Invoice/Create/{type?}/{id?}', 'Sales\InvoiceController@form')->name('Sales/Invoice/Create');

        Route::get('Sales/Invoice/Regenerate/{type?}/{id?}', 'Sales\InvoiceController@regenerate')->name('regenerate');

        Route::get('Sales/Invoice/View/{type?}/{id?}', 'Sales\InvoiceController@view')->name('Sales/Invoice/View');
        Route::get('Sales/Invoice/Create/pdf/{type?}/{id?}', 'Sales\InvoiceController@printinvoice')->name('Sales/Invoice/Create/pdf');
        Route::post('Sales/Invoice/Add', 'Sales\InvoiceController@save')->name('Sales/Invoice/Add');
        Route::post('Sales/TaxRate', 'Sales\InvoiceController@getTaxRate');
        Route::post('Sales/DiscountRate', 'Sales\InvoiceController@getDiscountRate');
        Route::post('invoiceData', 'Sales\InvoiceController@getInvoices');
        Route::post('invoice/Report', 'Sales\InvoiceController@report');


        // ****************************** Sales FMCG Module ***********************************//


        //Trade Offer
        Route::get('Sales_Fmcg/TradeOffer/List', 'Sale_fmcg\TradeController@index');
        Route::get('Sales_Fmcg/TradeOffer/Create/{id?}', 'Sale_fmcg\TradeController@form');
        Route::post('Sales_Fmcg/TradeOffer/Add', 'Sale_fmcg\TradeController@save');

        //Delivery Order
        Route::get('Sales_Fmcg/DeliveryOrder/List', 'Sale_fmcg\DeliveryOrderController@index');
        Route::get('Sales_Fmcg/DeliveryOrder/Create/{id?}', 'Sale_fmcg\DeliveryOrderController@form');
        Route::get('Sales_Fmcg/DeliveryOrder/View/{id}', 'Sale_fmcg\DeliveryOrderController@view');
        Route::get('Sales_Fmcg/DeliveryOrder/Createpdf/{id?}', 'Sale_fmcg\DeliveryOrderController@printdelivery');
        Route::post('Sales_Fmcg/DeliveryOrder/Add', 'Sale_fmcg\DeliveryOrderController@save');
        Route::post('Sales_Fmcg/SaleOrderApprovelDetail', 'Sale_fmcg\DeliveryOrderController@approvel_detail');
        Route::post('Inventory/getStock', 'Sale_fmcg\DeliveryOrderController@stock');

        //D.O Tracking
        Route::get('Sales_Fmcg/DoTracking/List', 'Sale_fmcg\DO_TrackingController@index');
        Route::get('Sales_Fmcg/DoTracking/Create/{id?}', 'Sale_fmcg\DO_TrackingController@form');
        Route::post('Sales_Fmcg/DoTracking/Add', 'Sale_fmcg\DO_TrackingController@save');
        Route::post('Sales_Fmcg/DeliveryOrderDetail', 'Sale_fmcg\DO_TrackingController@do_detail');
        Route::get('Sales_Fmcg/DoTracking/View/{id}', 'Sale_fmcg\DO_TrackingController@view');

        //Sales Order
        Route::get('Sales_Fmcg/SaleOrder/List', 'Sale_fmcg\SaleOrderController@index');
        Route::get('Sales_Fmcg/SaleOrder/Create/{id?}', 'Sale_fmcg\SaleOrderController@form');
        Route::post('Sales_Fmcg/SaleOrder/Add', 'Sale_fmcg\SaleOrderController@save');
        Route::get('Sales_Fmcg/SaleOrder/View/{id}', 'Sale_fmcg\SaleOrderController@view');
        Route::post('getTradeOffer', 'Sale_fmcg\SaleOrderController@getOffers');
        Route::post('applyRateCode', 'Sale_fmcg\SaleOrderController@getRateCode');
        Route::post('getBalance', 'Sale_fmcg\SaleOrderController@getCustomerBalance');
        Route::post('customerDiscount', 'Sale_fmcg\SaleOrderController@getCustomerDiscount');

        //Sale Order Approvel
        Route::get('Sales_Fmcg/SaleOrderApprovel/List', 'Sale_fmcg\SaleOrderApprovelController@index');
        Route::get('Sales_Fmcg/SaleOrderApprovel/Create/{id?}', 'Sale_fmcg\SaleOrderApprovelController@form');
        Route::post('Sales_Fmcg/SaleOrderApprovel/Add', 'Sale_fmcg\SaleOrderApprovelController@save');
        Route::post('Sales_Fmcg/SaleOrderDetail', 'Sale_fmcg\SaleOrderApprovelController@so_detail');
        Route::post('getApprovelTradeOffer', 'Sale_fmcg\SaleOrderApprovelController@getApprovelOffer');
        Route::post('applyRateCode', 'Sale_fmcg\SaleOrderController@getRateCode');

        // Geo Location
        Route::get('Sales_Fmcg/GeoLocation/List', 'Sale_fmcg\GeoLocationController@index');
        Route::get('Sales_Fmcg/GeoLocation/Create/{id?}', 'Sale_fmcg\GeoLocationController@form');
        Route::post('Sales_Fmcg/GeoLocation/Add', 'Sale_fmcg\GeoLocationController@save');
        Route::get('Sales_Fmcg/RemoveGeoLocation/{id}', 'Sale_fmcg\GeoLocationController@locationRemove');

        //Counter Sale Order
        Route::get('Sales_Fmcg/CounterSale/List', 'Sale_fmcg\CounterSaleOrderController@index');
        Route::Get('Sales_Fmcg/CounterSaleOrder/Create', 'Sale_fmcg\CounterSaleOrderController@form');

        //Rate Code
        Route::get('Sales_Fmcg/RateCode/List', 'Sale_fmcg\RateCodeController@index');
        Route::get('Sales_Fmcg/RateCode/Create/{id?}', 'Sale_fmcg\RateCodeController@form');
        Route::post('Sales_Fmcg/RateCode/Add', 'Sale_fmcg\RateCodeController@save');

        //Customer Discount
        Route::get('Sales_Fmcg/CustomerDiscount/List', 'Sale_fmcg\CustomerDiscountController@index');
        Route::get('Sales_Fmcg/CustomerDiscount/Create/{id?}', 'Sale_fmcg\CustomerDiscountController@form');
        Route::post('Sales_Fmcg/CustomerDiscount/Add', 'Sale_fmcg\CustomerDiscountController@save');

        //Sale Invoice
        Route::get('Sales_Fmcg/SaleInvoice/List', 'Sale_fmcg\SaleInvoiceController@index');
        Route::get('Sales_Fmcg/Print/{id}', 'Sale_fmcg\SaleInvoiceController@printinvoice');
        Route::get('Sales_Fmcg/SaleInvoice/Create/{id?}', 'Sale_fmcg\SaleInvoiceController@form');
        Route::get('Sales_Fmcg/SaleInvoice/View/{id}', 'Sale_fmcg\SaleInvoiceController@view');
        Route::post('Sales_Fmcg/do_detail', 'Sale_fmcg\SaleInvoiceController@do_detail');
        Route::post('Sales_Fmcg/SaleInvoice/Add', 'Sale_fmcg\SaleInvoiceController@save');

        //Counter Sale Order
        Route::get('Sales/CounterSaleOrder/List', 'Sale_fmcg\CounterSaleOrderController@index');
        Route::Get('Sales/CounterSaleOrder/Create', 'Sale_fmcg\CounterSaleOrderController@form');



        // ******************************  Support & Ticket  ***********************************//
        Route::get('Tmg/Listing/{type?}', 'Ticket\TicketController@list')->name('Tmg/Listing/me');
        Route::get('Tmg/Ticket/{id?}', 'Ticket\TicketController@ticket');
        Route::get('Tmg/View/{id}', 'Ticket\TicketController@ticketView');
        Route::post('Ticket/updateHistory', 'Ticket\TicketController@updateTicketHistory');
        Route::post('Ticket/update', 'Ticket\TicketController@updateTicket');
        Route::get('Tmg/Ticket/Delete/{id}', 'Ticket\TicketController@deleteTicket');
        Route::post('Tmg/Ticket/BulkUpdate', 'Ticket\TicketController@BulkTicket');
        Route::post('Tmg/Ticket/Search', 'Ticket\TicketController@TicketSearch')->name('search.ticket');
        Route::post('Tmg/Project/Search', 'Ticket\TicketController@TicketSearchProject')->name('search.ticket.project');
        Route::post('Tmg/Reports/Search', 'Ticket\TicketController@TicketSearchReports')->name('search.ticket.reports');

        Route::get('Reports', 'Ticket\TicketController@reports');





        // Route::get('getTicketList','Ticket\TicketController@getTicketList');


        Route::get('Tmg/Status/{type}', 'Ticket\TicketOptionsController@list')->name('Tmg/Status/{type}');
        Route::get('Tmg/Cagetory/{type}', 'Ticket\TicketOptionsController@list');
        Route::get('Tmg/Priority/{type}', 'Ticket\TicketOptionsController@list');
        Route::get('Tmg/option/{type}/{id?}', 'Ticket\TicketOptionsController@option');
        Route::post('Tmg/option/save/', 'Ticket\TicketOptionsController@optionAdd');
        Route::get('Tmg/Task/delete/{id}', 'Ticket\TicketOptionsController@taskDelete');



        //
        // ******************************Reports ***********************************//
        //General Ledger
        Route::get('Reports/GeneralLedger', 'Report\AccountsReportsController@generalLedger');
        Route::post('generalLedger', 'Report\AccountsReportsController@generalLedgerList');

        //Project Ledger
        Route::get('Reports/ProjectLedger', 'Report\AccountsReportsController@projectLedger');
        Route::post('projectLedger', 'Report\AccountsReportsController@projectLedgerList');

        // Chart of account
        Route::get('Reports/ChartofAccounts', 'Report\AccountsReportsController@coa');
        Route::post('ChartofAccounts', 'Report\AccountsReportsController@coaList');

        //Trail Balance
        Route::get('Reports/TrialBalance', 'Report\AccountsReportsController@trialBalance');
        Route::post('TrialBalance', 'Report\AccountsReportsController@trialBalanceList');

        //Income Statement
        Route::get('Reports/IncomeStatement', 'Report\AccountsReportsController@incomeStatement');
        Route::post('IncomeStatement', 'Report\AccountsReportsController@incomeStatementList');

        //Balance Sheet
        Route::get('Reports/BalanceSheet', 'Report\AccountsReportsController@balanceSheet');
        Route::post('BalanceSheet', 'Report\AccountsReportsController@balanceSheetList');


        //Books (Cash=10 and Bank=9)
        Route::get('Reports/Book/List/{coa_id}', 'Report\AccountsReportsController@book');
        Route::post('Reports/CashBook/getBookDetails', 'Report\AccountsReportsController@bookDetails');

        //Product Detail
        Route::get('Reports/ProductDetail/List', 'Report\InventoryReportsController@product');
        Route::post('Reports/productDetail', 'Report\InventoryReportsController@productDetail');

        //CustomerOutstanding
        Route::get('Reports/CustomerOutstanding/List', 'Report\AccountsReportsController@customerOutstanding');
        Route::post('customerOutstanding', 'Report\AccountsReportsController@customerOutstandingDetail');

        //CustomerOutstandingSummery
        Route::get('Reports/CustomerOutstandingSummery/List', 'Report\AccountsReportsController@customerSummery');
        Route::post('customerOutstandingSummery', 'Report\AccountsReportsController@customerSummeryDetail');

        //Bank Reconciliation
        Route::get('Reports/BankReconciliation/List', 'Report\BankReconciliationController@index');
        Route::post('bankBookReconciliation', 'Report\BankReconciliationController@bankBookDetails');
        Route::post('updateReconciliation', 'Report\BankReconciliationController@updateReconciliation');

        //pending sale order
        Route::get('Reports/PendingSaleOrder/List', 'Report\SaleFmcgReportsController@index');
        Route::post('getPendingSalesOrder', 'Report\SaleFmcgReportsController@pendingSalesOrder');

        //vendor Grn Report
        Route::get('Reports/GoodReceived/List', 'Report\InventoryReportsController@printtcpdflist');
        Route::post('Reports/GoodReceived/tcpdf', 'Report\InventoryReportsController@printtcpdf');

        //Voucher Print
        Route::get('Accounts/CashVoucherPrint/{id}', 'Report\AccountsReportsController@printVoucher');
        Route::get('Accounts/JournalVoucherPrint/{id}', 'Report\AccountsReportsController@printJVoucher');

        //Sale Invoice Print
        Route::get('Reports/SaleInvoicePrint/{type?}', 'Report\SaleFmcgReportsController@saleInvoiceForm');
        Route::post('Reports/SaleInvoicePrint/Create', 'Report\SaleFmcgReportsController@SaleInvoicePrint');







        // ******************************Vendor ***********************************//

        //Vendor Category
        Route::get('Vendor/VendorCategory/List', 'Vendor\VendorCategoryController@index')->name('Vendor/VendorCategory/List');
        Route::get('Vendor/VendorCategory/Create/{id?}', 'Vendor\VendorCategoryController@form');
        Route::post('Vendor/VendorCategory/Add', 'Vendor\VendorCategoryController@save');
        Route::get('Vendor/VendorCategory/removeCategory/{id}', 'Vendor\VendorCategoryController@hide');

        //Vendor
        Route::get('Vendor/VendorManagement/List', 'Vendor\VendorController@index');
        Route::get('Vendor/VendorManagement/Create/{id?}', 'Vendor\VendorController@form');
        Route::post('Vendor/VendorManagement/Add', 'Vendor\VendorController@save');
        Route::get('Vendor/VendorManagement/Views/{id?}', 'Vendor\VendorController@view');




        // ******************************Vendor ***********************************//

        //Vendor Category
        Route::get('edConsult/List', 'EdConsult\edConsultController@list')->name('EdConsult/List');
        Route::get('edConsult/', 'EdConsult\edConsultController@form')->name('EdConsult/');

        //Route::post('Vendor/VendorCategory/Add','Vendor\VendorCategoryController@save');
        //Route::get('Vendor/removeCategory/{id}','Vendor\VendorCategoryController@hide');

        //////////////////////////////////////////////////////////////////////////////////////////////
        Route::get('/clear-cache', function () {
            Artisan::call('cache:clear');
            return "Cache is cleared";
        });

        // ------------------------- Do not create route below this line. ------------------------
        // ************************************ Following are theme routs **************************

        /* Dashboard */
        Route::get('dashboard', function () {
            return redirect('dashboard/index');
        });
        Route::get('dashboard/index', 'DashboardController@index')->name('dashboard.index');

        /* Profile */
        Route::get('profile', function () {
            return redirect('profile/my-profile');
        });
        Route::get('profile/my-profile', 'ProfileController@myProfile')->name('profile.my-profile');

        /* App */
        Route::get('app', function () {
            return redirect('app/inbox');
        });
        Route::get('app/inbox', 'AppController@inbox')->name('app.inbox');
        Route::get('app/compose', 'AppController@compose')->name('app.compose');
        Route::get('app/single', 'AppController@single')->name('app.single');
        Route::get('app/chat', 'AppController@chat')->name('app.chat');

        Route::get('app/contact-list', 'AppController@contactList')->name('app.contact-list');

        /* Project */
        Route::get('project', function () {
            return redirect('project/project-list');
        });
        Route::get('project/project-list', 'ProjectController@projectList')->name('project.project-list');
        Route::get('project/taskboard', 'ProjectController@taskboard')->name('project.taskboard');
        Route::get('project/ticket-list', 'ProjectController@ticketList')->name('project.ticket-list');
        Route::get('project/ticket-detail', 'ProjectController@ticketDetail')->name('project.ticket-detail');

        /* File Manager */
        Route::get('file-manager', function () {
            return redirect('file-manager/all');
        });
        Route::get('file-manager/all', 'FileManagerController@all')->name('file-manager.all');
        Route::get('file-manager/documents', 'FileManagerController@documents')->name('file-manager.documents');
        Route::get('file-manager/media', 'FileManagerController@media')->name('file-manager.media');
        Route::get('file-manager/image', 'FileManagerController@image')->name('file-manager.image');

        /* Blog */
        Route::get('blog', function () {
            return redirect('blog/dashboard');
        });
        Route::get('blog/dashboard', 'BlogController@dashboard')->name('blog.dashboard');
        Route::get('blog/list', 'BlogController@list')->name('blog.list');
        Route::get('blog/grid', 'BlogController@grid')->name('blog.grid');
        Route::get('blog/detail', 'BlogController@detail')->name('blog.detail');
        // Blog category
        Route::get('blog/category', 'BlogController@categoryList')->name('blog.category');
        Route::get('blog/category/create/{id?}', 'BlogController@createCategory')->name('blog.create.category');
        Route::post('blog/Category/save', 'BlogController@save')->name('blog.category.save');
        Route::get('blog/removeCategory/{id}', 'BlogController@delete')->name('blog.removeCategory');

        // Routes for Blog post crud

        Route::get('blog/post', 'BlogController@posts')->name('blog.posts');
        Route::get('blog/post/form/{id?}', 'BlogController@postForm')->name('blog.postForm');
        Route::post('blog/post/save', 'BlogController@postSave')->name('blog.post.save');
        Route::post('blog/post/update/{id}', 'BlogController@postUpdate')->name('blog.post.update');
        Route::get('blog/post/view/{id}', 'BlogController@viewPost')->name('blog.viewPost');
        Route::get('blog/removepost/{id}', 'BlogController@deletePost')->name('blog.delete');







        /* Ecommerce */
        Route::get('ecommerce', function () {
            return redirect('ecommerce/dashboard');
        });
        Route::get('ecommerce/dashboard', 'EcommerceController@dashboard')->name('ecommerce.dashboard');
        Route::get('ecommerce/product', 'EcommerceController@product')->name('ecommerce.product');
        Route::get('ecommerce/product-list', 'EcommerceController@productList')->name('ecommerce.product-list');
        Route::get('ecommerce/product-detail', 'EcommerceController@productDetail')->name('ecommerce.product-detail');

        /* components */
        Route::get('components', function () {
            return redirect('components/ui');
        });
        Route::get('components/ui', 'ComponentsController@ui')->name('components.ui');
        Route::get('components/alerts', 'ComponentsController@alerts')->name('components.alerts');
        Route::get('components/collapse', 'ComponentsController@collapse')->name('components.collapse');
        Route::get('components/colors', 'ComponentsController@colors')->name('components.colors');
        Route::get('components/dialogs', 'ComponentsController@dialogs')->name('components.dialogs');
        Route::get('components/list', 'ComponentsController@list')->name('components.list');
        Route::get('components/media', 'ComponentsController@media')->name('components.media');
        Route::get('components/modals', 'ComponentsController@modals')->name('components.modals');
        Route::get('components/notifications', 'ComponentsController@notifications')->name('components.notifications');
        Route::get('components/progressbars', 'ComponentsController@progressbars')->name('components.progressbars');
        Route::get('components/range', 'ComponentsController@range')->name('components.range');
        Route::get('components/sortable', 'ComponentsController@sortable')->name('components.sortable');
        Route::get('components/tabs', 'ComponentsController@tabs')->name('components.tabs');
        Route::get('components/waves', 'ComponentsController@waves')->name('components.waves');

        /* Font Icons */
        Route::get('icons', function () {
            return redirect('icons/material');
        });
        Route::get('icons/material', 'IconsController@material')->name('icons.material');
        Route::get('icons/themify', 'IconsController@themify')->name('icons.themify');
        Route::get('icons/weather', 'IconsController@weather')->name('icons.weather');

        /* Form */
        Route::get('form', function () {
            return redirect('form/basic');
        });
        Route::get('form/basic', 'FormController@basic')->name('form.basic');
        Route::get('form/advanced', 'FormController@advanced')->name('form.advanced');
        Route::get('form/examples', 'FormController@examples')->name('form.examples');
        Route::get('form/validation', 'FormController@validation')->name('form.validation');
        Route::get('form/wizard', 'FormController@wizard')->name('form.wizard');
        Route::get('form/editors', 'FormController@editors')->name('form.editors');
        Route::get('form/upload', 'FormController@upload')->name('form.upload');
        Route::get('form/summernote', 'FormController@summernote')->name('form.summernote');

        /* Tables */
        Route::get('tables', function () {
            return redirect('tables/normal');
        });
        Route::get('tables/normal', 'TablesController@normal')->name('tables.normal');
        Route::get('tables/datatable', 'TablesController@datatable')->name('tables.datatable');
        Route::get('tables/editable', 'TablesController@editable')->name('tables.editable');
        Route::get('tables/footable', 'TablesController@footable')->name('tables.footable');
        Route::get('tables/color', 'TablesController@color')->name('tables.color');

        /* Chart */
        Route::get('chart', function () {
            return redirect('chart/echarts');
        });
        Route::get('chart/echarts', 'ChartController@echarts')->name('chart.echarts');
        Route::get('chart/c3', 'ChartController@c3')->name('chart.c3');
        Route::get('chart/morris', 'ChartController@morris')->name('chart.morris');
        Route::get('chart/flot', 'ChartController@flot')->name('chart.flot');
        Route::get('chart/chartjs', 'ChartController@chartjs')->name('chart.chartjs');
        Route::get('chart/sparkline', 'ChartController@sparkline')->name('chart.sparkline');
        Route::get('chart/knob', 'ChartController@knob')->name('chart.knob');

        /* Widgets */
        Route::get('widgets', function () {
            return redirect('widgets/app');
        });
        Route::get('widgets/app', 'WidgetsController@app')->name('widgets.app');
        Route::get('widgets/data', 'WidgetsController@data')->name('widgets.data');


        /* Pages */
        Route::get('pages', function () {
            return redirect('pages/blank-page');
        });
        Route::get('pages/blank', 'PagesController@blank')->name('pages.blank');
        Route::get('pages/gallery', 'PagesController@gallery')->name('pages.gallery');
        Route::get('pages/invoices1', 'PagesController@invoices1')->name('pages.invoices1');
        Route::get('pages/invoices2', 'PagesController@invoices2')->name('pages.invoices2');
        Route::get('pages/pricing', 'PagesController@pricing')->name('pages.pricing');
        Route::get('pages/profile', 'PagesController@profile')->name('pages.profile');
        Route::get('pages/search', 'PagesController@search')->name('pages.search');
        Route::get('pages/timeline', 'PagesController@timeline')->name('pages.timeline');

        /* Maps */
        Route::get('map', function () {
            return redirect('map/google');
        });
        Route::get('map/yandex', 'MapController@yandex')->name('map.yandex');
        Route::get('map/jvector', 'MapController@jvector')->name('map.jvector');
        Auth::routes();

        // Web Routes

        // Route::get('/', function () { return view('web.home');});
        // Route::get('/login-with-us', function () { return view('web.Auth.login');});

        //Individual
        Route::get('/Individual-Registration', [IndividualController::class, 'individualReg']);
        Route::post('/register', [IndividualController::class, 'individual'])->name('individual.register');

        //Business
        Route::get('/Business-Registration', function () {
            return view('web.Auth.business');
        });
        Route::get('/Business-Registration-Detail', function () {
            return view('web.Auth.businessDetail');
        });
        Route::post('/create-business', [BusinessController::class, 'createBusiness'])->name('create_business');
        Route::post('/Business-Registration-Detail', [BusinessController::class, 'createBusinessDetail'])->name('Business-Registration-Detail');

        //Accountant and Bookkeeper
        Route::get('/Accountant&Bookkeeper', function () {
            return view('web.Auth.accountant&Bookkeeper');
        });
        Route::post('/create-Accountant', [Accountant_BookkeeperController::class, 'createAccountantBookkeeper'])->name('create_Accountant');






        // Web Routes

        // Route::get('/', function () { return view('web.home');});
        // Route::get('/login-with-us', function () { return view('web.Auth.login');});

        //Individual
        Route::get('/Individual-Registration', [IndividualController::class, 'individualReg']);
        Route::post('/register', [IndividualController::class, 'individual'])->name('individual.register');

        //Business
        Route::get('/Business-Registration', function () {
            return view('web.Auth.business');
        });
        Route::get('/Business-Registration-Detail', function () {
            return view('web.Auth.businessDetail');
        });
        Route::post('/create-business', [BusinessController::class, 'createBusiness'])->name('create_business');
        Route::post('/Business-Registration-Detail', [BusinessController::class, 'createBusinessDetail'])->name('Business-Registration-Detail');

        //Accountant and Bookkeeper
        Route::get('/Accountant&Bookkeeper', function () {
            return view('web.Auth.accountant&Bookkeeper');
        });
        Route::post('/create-Accountant', [Accountant_BookkeeperController::class, 'createAccountantBookkeeper'])->name('create_Accountant');

        /* Business Intelligence */

        Route::get('Business_Intelligence/Graphs/List', [BIController::class, 'index']);


        // Documents Manager

        Route::get('Document/Create/Document/{id?}', [DocumentController::class, 'CreateDocument']);
        Route::post('document/create_document', [DocumentController::class, 'AddDocument']);
        Route::get('Document/All/Documents', [DocumentController::class, 'allDocuments']);
        Route::get('Document/Delete/Document/{id}', [DocumentController::class, 'DeleteDocument']);
        Route::get('Document/View/Document/{id}', [DocumentController::class, 'ViewDocument']);
        Route::get('Document/Delete/Attchment/{id}', [DocumentController::class, 'DeleteDocumentAttachment']);
        Route::get('Document/Share/{id}', [DocumentController::class, 'ShareDocument']);
        Route::post('Document/Share/Save/{id}', [DocumentController::class, 'SaveShareDocument']);


        Route::get('mails', [EmailController::class, 'fetchEmails']);

        //Route::get('Purchase/PurchaseExpenses','Purchase\PurchaseExpensesController@form');



        /**********************************************
         *
         * HCM Leaves
         *
         ***********************************************/

        Route::get('hcm-leaves' , [\App\Http\Controllers\Hcm\LeaveController::class, 'index'])
            ->name('leave.index');

        Route::get('hcm-leaves/{id}/show', [\App\Http\Controllers\Hcm\LeaveController::class, 'show'])
            ->name('leave.show');
        Route::get('hcm-leaves/create' , [\App\Http\Controllers\Hcm\LeaveController::class, 'create'])
            ->name('leave.create');

        Route::post('hcm-leaves/store' , [\App\Http\Controllers\Hcm\LeaveController::class, 'store'])
            ->name('leave.store');


        Route::get('hcm-leaves/{id}/edit' , [\App\Http\Controllers\Hcm\LeaveController::class, 'edit'])
            ->name('leave.edit');

        Route::put('hcm-leaves/{id}/update' , [\App\Http\Controllers\Hcm\LeaveController::class, 'update'])
            ->name('leave.update');

        Route::delete('hcm-leaves/{id}', [\App\Http\Controllers\Hcm\LeaveController::class, 'destroy'])
            ->name('leave.destroy');

        /**********************************************
         *
         * HCM Salary
         *
         ***********************************************/

        Route::get('hcm-salary' , [\App\Http\Controllers\Hcm\SalaryController::class, 'index'])->name('salary.index');

        Route::get('hcm-salary/create' , [\App\Http\Controllers\Hcm\SalaryController::class, 'create'])
            ->name('salary.create');

        Route::post('hcm-salary/store' , [\App\Http\Controllers\Hcm\SalaryController::class, 'store'])
            ->name('salary.store');

        Route::get('hcm-salary/{id}/edit' , [\App\Http\Controllers\Hcm\SalaryController::class , 'edit'])
            ->name('salary.edit');

        Route::put('hcm-salary/{id}/update' , [\App\Http\Controllers\Hcm\SalaryController::class, 'update'])
            ->name('salary.update');


        Route::get('hcm-salary/{id}/show' , [\App\Http\Controllers\Hcm\SalaryController::class, 'show'])
            ->name('salary.show');

        Route::delete('hcm-salary/{id}' , [\App\Http\Controllers\Hcm\SalaryController::class, 'destory'])
            ->name('salary.destory');





        /**********************************************
         *
         * HCM DEPARTMENTS
         *
         ***********************************************/

        Route::get('hcm-departments', [DepartmentController::class, 'index'])
            ->name('hcm.department');
        Route::get('hcm-department/create' , [DepartmentController::class, 'create'])
            ->name('hcm.department.create');
        Route::post('/hcm-department', [DepartmentController::class, 'store'])
            ->name('hcm.department.store');
        Route::get('hcm-department/{id}/edit' , [DepartmentController::class, 'edit'])
            ->name('hcm.department.edit');
        Route::put('hcm-department/{id}' , [DepartmentController::class, 'update'])
            ->name('hcm.department.update');
        Route::delete('hcm-department/{id}' , [DepartmentController::class, 'destroy'])
            ->name('hcm.department.delete');


        /**********************************************
         *
         * HCM SHIFT routes
         *
         ***********************************************/

        Route::get('hcm/shifts' , [\App\Http\Controllers\Hcm\ShiftController::class, 'index'])
            ->name('hcm.shifts');

        Route::get('hcm/shifts/create' , [\App\Http\Controllers\Hcm\ShiftController::class, 'create'])
            ->name('hcm.shifts.create');
        Route::post('hcm/shift' , [\App\Http\Controllers\Hcm\ShiftController::class, 'store'])
            ->name('hcm.shifts.store');

        Route::get('hcm/shift/{id}/edit' , [\App\Http\Controllers\Hcm\ShiftController::class, 'edit'])
            ->name('hcm.shift.edit');

        Route::put('hcm/shift/{id}' , [\App\Http\Controllers\Hcm\ShiftController::class, 'update'])
            ->name('hcm.shift.update');

        Route::get('hcm/shift/show/{id}', [\App\Http\Controllers\Hcm\ShiftController::class , 'show'])
            ->name('shift.show');


        Route::delete('hcm/shift/{id}' , [\App\Http\Controllers\Hcm\ShiftController::class, 'destroy'])
            ->name('hcm.shift.delete');




        /*****************************************
         *
         *  HCM EMPLOYEE ROUTES
         *f
         ******************************************/

        Route::get('hcm-employees' , [\App\Http\Controllers\Hcm\EmployeeController::class, 'index'])
            ->name('employee.index');

        Route::prefix('/hcm-employee')->group(function () {

            Route::get('/create', [\App\Http\Controllers\Hcm\EmployeeController::class, 'create'])
                ->name('employee.create');

            Route::post('/store', [\App\Http\Controllers\Hcm\EmployeeController::class , 'store'])
                ->name('employee.store');

            Route::get('/{id}/details' , [\App\Http\Controllers\Hcm\EmployeeController::class, 'empDetail'])
                ->name('employee.detail');

            Route::put('/{id}/personal-info' , [\App\Http\Controllers\Hcm\EmployeeController::class, 'empPersonalInfo'])
                ->name('employee.personal-info');

            Route::post('/employement-history' , [\App\Http\Controllers\Hcm\EmployeeController::class, 'employementHistory'])
                ->name('employee.employement-history');

            Route::put('/{id}/employement-history' , [\App\Http\Controllers\Hcm\EmployeeController::class, 'updateEmployementHistory'])
                ->name('employee.update-employement-history');

            Route::get('Employement-history/download/{id}', [\App\Http\Controllers\Hcm\EmployeeController::class, 'downloadExperienceLetter'])
                ->name('employee.download-experience-letter');

            Route::delete('/{id}/employement-history' , [\App\Http\Controllers\Hcm\EmployeeController::class, 'destroyEmploymentHistory'])
                ->name('employee.delete-employement-history');



            /***************************************************************
             *
             * Emergency Contact Routes
             *
             ****************************************************************/

            Route::post('/emergency-contact', [\App\Http\Controllers\Hcm\EmergencyContactController::class, 'store'])
                ->name('emergency-contact.create');

            Route::put('/emergency-contact/{id}', [\App\Http\Controllers\Hcm\EmergencyContactController::class, 'update'])
                ->name('emergency-contact.update');

            Route::delete('/emergency-contact/{id}' , [\App\Http\Controllers\Hcm\EmergencyContactController::class, 'destory'])
                ->name('emergency-contact.delete');



            /***************************************************************
             *
             * Bank Routes
             *
             ****************************************************************/

            Route::post('/bank-detail', [\App\Http\Controllers\Hcm\BankController::class, 'store'])
                ->name('bank-detail.store');

            Route::put('/bank-detail/{id}', [\App\Http\Controllers\Hcm\BankController::class, 'update'])
                ->name('bank-detail.update');


            Route::delete('/bank-detail/{id}', [\App\Http\Controllers\Hcm\BankController::class, 'destroy'])
                ->name('bank-detail.delete');



            /***************************************************************
             *
             * Education Routes
             *
             ****************************************************************/

            Route::post('/education-detail', [\App\Http\Controllers\Hcm\EducationController::class, 'store'])
                ->name('education-detail.store');

            Route::put('/education-detail/{id}', [\App\Http\Controllers\Hcm\EducationController::class, 'update'])
                ->name('education-detail.update');

            Route::get('/education-detail/download/{id}', [\App\Http\Controllers\Hcm\EducationController::class, 'downloadDoc'])
                ->name('education-detail.download');


            Route::delete('/education-detail/{id}', [\App\Http\Controllers\Hcm\EducationController::class, 'destroy'])
                ->name('education-detail.delete');





            /***************************************************************
             *
             * Social Media Routes
             *
             ****************************************************************/

            Route::post('/social-media-detail', [\App\Http\Controllers\Hcm\SocialMediaController::class, 'store'])
                ->name('social-media-detail.store');

//            Route::put('/education-detail/{id}', [\App\Http\Controllers\Hcm\EducationController::class, 'update'])
//                ->name('education-detail.update');
//
//
//            Route::delete('/education-detail/{id}', [\App\Http\Controllers\Hcm\EducationController::class, 'destroy'])
//                ->name('education-detail.delete');


            /***************************************************************
             *
             * Social Media Routes
             *
             ****************************************************************/

            Route::post('/skill-detail', [\App\Http\Controllers\Hcm\SkillController::class, 'store'])
                ->name('skill-detail.store');

            Route::put('/skill-detail/{id}', [\App\Http\Controllers\Hcm\SkillController::class, 'update'])
                ->name('skill-detail.update');




            Route::delete('/skill-detail/{id}', [\App\Http\Controllers\Hcm\SkillController::class, 'destroy'])
                ->name('skill-detail.delete');



            /***************************************************************
             *
             * Documents Routes
             *
             ****************************************************************/

            Route::post('/emp-documents', [\App\Http\Controllers\Hcm\DocumentsController::class, 'store'])
                ->name('emp-documents.store');

            Route::get('/emp-documents/download/{id}', [\App\Http\Controllers\Hcm\DocumentsController::class, 'downloadDoc'])
                ->name('emp-documents.download');

            Route::put('/emp-documents/{id}', [\App\Http\Controllers\Hcm\DocumentsController::class, 'update'])
                ->name('emp-documents.update');


            Route::delete('/emp-documents/{id}', [\App\Http\Controllers\Hcm\DocumentsController::class, 'destroy'])
                ->name('emp-documents.delete');



            /***************************************************************
             *
             * Assets Routes
             *
             ****************************************************************/

            Route::post('/asset-detail', [\App\Http\Controllers\Hcm\AssetController::class, 'store'])
                ->name('asset-detail.store');

            Route::put('/asset-detail/{id}', [\App\Http\Controllers\Hcm\AssetController::class, 'update'])
                ->name('asset-detail.update');


            Route::delete('/asset-detail/{id}', [\App\Http\Controllers\Hcm\AssetController::class, 'destroy'])
                ->name('asset-detail.delete');



            /***************************************************************
             *
             * Employment Detail Routes
             *
             ****************************************************************/

            Route::post('/employment-detail', [\App\Http\Controllers\Hcm\EmploymentDetailController::class, 'store'])
                ->name('employment-detail.store');

            Route::put('/employment-detail/{id}', [\App\Http\Controllers\Hcm\EmploymentDetailController::class, 'update'])
                ->name('employment-detail.update');

//
//            Route::delete('/asset-detail/{id}', [\App\Http\Controllers\Hcm\AssetController::class, 'destroy'])
//                ->name('asset-detail.delete');





        });


        Route::view('hcm-employee/profile' , 'hcm.employee.show')->name('employee.show');
    });
});
