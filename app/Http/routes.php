<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::group(["namespace" => 'Admin', "prefix" => "admin"], function() {
     
    Route::get('', ['uses'          => 'AdminController@login', 'as' => 'admin.login']);
    Route::post('', ['uses'         => 'AdminController@loginCheck', 'as' => 'admin.logincheck']);
    Route::get('logout', ['uses'    => 'AdminController@logout', 'as' => 'admin.logout']);
});

//User Routes
    Route::get('/',['uses' => 'WelcomeController@index', 'as' => 'welcome']);
    Route::get('/registration',['uses' => 'WelcomeController@registration', 'as' => 'user.registration']);
    Route::post('/user/add',['uses' => 'WelcomeController@add', 'as' => 'user.add']);
    Route::post('/user/verification',['uses' => 'WelcomeController@verification', 'as' => 'admin.user.verification']);
    Route::post('/user/optcode',['uses' => 'WelcomeController@optCode', 'as' => 'admin.user.optCode']);
    Route::get('email/confirmation/{code}',['uses' => 'WelcomeController@confirmation', 'as' => 'admin.user.confirmation']);

     Route::get('forget',['uses'  => 'WelcomeController@forget', 'as'=>'user.forget']);
    Route::post('token/send',['uses'  => 'WelcomeController@tokenSend', 'as'=>'user.token.send']);
    Route::get('password/reset/{token}',['uses'  => 'WelcomeController@passwordReset', 'as'=>'user.password.reset']);
    Route::post('password/set',['uses'  => 'WelcomeController@passwordSet', 'as'=>'user.password.set']);


    Route::get('product/{ID}',['uses' => 'WelcomeController@productShow', 'as' => 'user.product.show']);
	Route::get('search/product',['uses' => 'WelcomeController@productSearch', 'as' => 'user.search.product']);
    Route::get('product/detail/{ID}',['uses' => 'WelcomeController@productDetailShow', 'as' => 'product.detail.show']);

    //ROUTES FOR CONTACT ,AMAZON ,FAQ
    Route::get('user/contact',['uses' => 'WelcomeController@contact', 'as' => 'user.contact']);
    Route::post('user/contact/add',['uses' => 'WelcomeController@contactadd', 'as' => 'contact.add']);

    Route::get('user/faq',['uses' => 'WelcomeController@faq', 'as' => 'user.faq']);
    Route::get('user/amazonseller',['uses' => 'WelcomeController@amazonseller', 'as' => 'user.amazonseller']);
    Route::get('about',['uses' => 'WelcomeController@about', 'as' => 'about']);
    Route::get('privacy_policy',['uses' => 'WelcomeController@privacy_policy', 'as' => 'privacy_policy']);
    Route::get('term_condition',['uses' => 'WelcomeController@term_condition', 'as' => 'term_condition']);

     Route::get('blog',['uses' => 'WelcomeController@blog', 'as' => 'user.blog']);
     Route::get('blog/detail/{ID}',['uses' => 'WelcomeController@blogDetail', 'as' => 'blog.detail']);



    Route::get('product/confirmation/product/{code}',['uses' => 'WelcomeController@productconfirmation', 'as' => 'admin.user.product.confirmation']);


    Route::group(["namespace" => 'User', "prefix" => "user"], function() {
     
    Route::get('', ['uses'          => 'UserController@login', 'as' => 'user.login']);
    Route::post('', ['uses'         => 'UserController@loginCheck', 'as' => 'user.logincheck']);
    Route::get('logout', ['uses'    => 'UserController@logout', 'as' => 'user.logout']);
   


    
});


     Route::group(["namespace" => 'User','middleware'=>'usercheck', "prefix" => "user"], function() {

     Route::get('dashboard',['uses' => 'UserController@dashboard', 'as' => 'user.dashboard']);
        //USER REVIEW
     Route::post('dashboard',['uses' => 'UserController@review', 'as' => 'user.review.submit']);
       //USER AMAZON URL
      Route::post('dashboard',['uses' => 'UserController@amazon', 'as' => 'user.amazon.review']);

      //user History page
      Route::get('history',['uses' => 'UserController@userHistory', 'as' => 'user.history']);
      Route::get('history/search',['uses' => 'UserController@searchProduct', 'as' => 'user.product.search']);
      Route::get('user/review/{ID}',['uses' => 'UserController@userReview', 'as' => 'user.show.review']);

       //user Edit page
      Route::get('edit',['uses' => 'UserController@userEdit', 'as' => 'user.edit']);
      Route::post('edit',['uses' => 'UserController@userEditDetail', 'as' => 'user.edit.detail']);

      //user change password page
      Route::get('password',['uses' => 'UserController@userPassword', 'as' => 'user.password']);
      Route::post('password/change',['uses' => 'UserController@passwordChange', 'as' => 'user.change.password']);



});


Route::group(["namespace" => 'Admin','middleware'=>'authcheck', "prefix" => "admin"], function() {
   
    Route::get('changepasswordform', ['uses' => 'AdminDashboardController@changePasswordView', 'as' => 'change.password.view']);
    Route::post('changepasswordform', ['uses' => 'AdminDashboardController@changePassword', 'as' => 'admin.change.password']);

    Route::get('dashboard',['uses' => 'AdminDashboardController@dashboard', 'as' => 'admin.dashboard']);

    Route::get('settings/general/view', ['uses'=>   'AdminDashboardController@generalSettings','as'   =>'admin.general.settings']);
    Route::get('settings/profile/view', ['uses'=>   'AdminDashboardController@profileSettings','as'   =>'admin.profile.settings']);
    Route::post('settings/general',  ['uses'=>'AdminDashboardController@updateGeneralSettings','as'=>'admin.update.general.settings']);
    Route::post('settings/profile', ['uses'=>'AdminDashboardController@updateProfileSettings','as'=>'admin.update.profile.settings']);

    Route::post('service/engineer/action',['uses'=> 'AdminServiceController@addOrEdit','as' =>  'admin.service.engineer.addoredit' ]);
    Route::post('service/engineer/{ID}', ['uses' => 'AdminServiceController@serviceEngineerDetails','as' => 'admin.service.engineer.details'

        ]);
    Route::resource('service/engineer', 'AdminServiceController', ['except' => ['edit']]);

    //Company routes

    Route::get('company', ['uses' => 'AdminCompanyController@index','as' => 'admin.service.company.index']);
    Route::get('company/showData', ['uses' => 'AdminCompanyController@create','as' => 'admin.service.company.create']);
    Route::get('company/add',['uses'=> 'AdminCompanyController@add','as' =>  'admin.service.company.add' ]);
    Route::post('company/adddata',['uses'=> 'AdminCompanyController@addData','as' =>  'admin.company.add' ]);
    Route::get('company/deletedata/{ID}',['uses'=> 'AdminCompanyController@destroy','as' =>  'admin.service.company.destroy' ]);
    Route::get('company/edit/{ID}',['uses'=> 'AdminCompanyController@edit','as' =>  'admin.service.company.edit' ]);
    Route::post('company/editdata',['uses'=> 'AdminCompanyController@editData','as' =>  'admin.company.edit' ]);
    Route::get('company/view/{ID}',['uses'=> 'AdminCompanyController@view','as' =>  'admin.service.company.view' ]);

     //Product Routes
     Route::get('product/add',['uses'=> 'AdminProductController@add','as' =>  'admin.service.product.add' ]);
     Route::post('product/adddata',['uses'=> 'AdminProductController@addData','as' =>  'admin.product.add' ]);
     Route::get('product', ['uses' => 'AdminProductController@index','as' => 'admin.service.product.index']);
     Route::get('product/showData', ['uses' => 'AdminProductController@create','as' => 'admin.service.product.create']);
     Route::get('product/deletedata/{ID}',['uses'=> 'AdminProductController@destroy','as' =>  'admin.service.product.destroy' ]);
     Route::get('product/view/{ID}',['uses'=> 'AdminProductController@view','as' =>  'admin.service.product.view' ]);
     Route::get('product/edit/{ID}',['uses'=> 'AdminProductController@edit','as' =>  'admin.service.product.edit' ]);
     Route::post('product/editdata',['uses'=> 'AdminProductController@editData','as' =>  'admin.product.edit' ]);
     Route::get('product/send/{ID}',['uses'=> 'AdminProductController@sendPromotion','as' =>  'admin.service.product.send' ]);


     Route::post('product/verification', ['uses' => 'AdminProductController@verification','as' => 'admin.service.product.verified']);
     Route::post('product/status',['uses'=> 'AdminProductController@status','as' =>  'admin.service.product.productStatus' ]);

     //USER Routes
     Route::get('user', ['uses' => 'AdminUserController@index','as' => 'admin.service.user.index']);
     Route::get('user/showData', ['uses' => 'AdminUserController@create','as' => 'admin.service.user.create']);
     Route::get('user/view/{ID}',['uses'=> 'AdminUserController@view','as' =>  'admin.service.user.view' ]);
     Route::get('user/amazon/status/{ID}',['uses'=> 'AdminUserController@amazonUrl','as' =>  'admin.service.user.amazonUrl' ]);
     Route::get('user/add',['uses'=> 'AdminUserController@add','as' =>  'admin.service.user.add' ]);
     Route::post('user/adddata',['uses'=> 'AdminUserController@addData','as' =>  'admin.user.add' ]);
     Route::get('user/edit/{ID}',['uses'=> 'AdminUserController@edit','as' =>  'admin.service.user.edit' ]);
     Route::post('user/editdata',['uses'=> 'AdminUserController@editData','as' =>  'admin.user.edit' ]);

     Route::post('user/status',['uses'=> 'AdminUserController@status','as' =>  'admin.service.user.userStatus' ]);

     Route::post('amazon/review/status',['uses'=> 'AdminUserController@amazonreviewstatus','as' =>  'admin.review.verify' ]);

     //CATEGORY ADD
    Route::get('category', ['uses' => 'AdminCategoryController@index','as' => 'admin.service.category.index']);
    Route::get('category/showData', ['uses' => 'AdminCategoryController@create','as' => 'admin.service.category.create']);
    Route::get('category/add',['uses'=> 'AdminCategoryController@add','as' =>  'admin.service.category.add' ]);
    Route::post('category/adddata',['uses'=> 'AdminCategoryController@addData','as' =>  'admin.category.add' ]);
    Route::get('category/deletedata/{ID}',['uses'=> 'AdminCategoryController@destroy','as' =>  'admin.service.category.destroy' ]);
    Route::get('category/edit/{ID}',['uses'=> 'AdminCategoryController@edit','as' =>  'admin.service.category.edit' ]);
    Route::post('category/editdata',['uses'=> 'AdminCategoryController@editData','as' =>  'admin.category.edit' ]);

    //NOTES ADD FOR Company

    Route::post('company/addnote',['uses'=> 'AdminCompanyController@addNotes','as' =>  'admin.service.company.note' ]);
    Route::post('company/editnote',['uses'=> 'AdminCompanyController@editNotes','as' =>  'admin.service.company.noteEdit' ]);

    //NOTES ADD FOR PRODUCT

    Route::post('product/addnote',['uses'=> 'AdminProductController@addNotes','as' =>  'admin.service.note' ]);
    Route::post('product/editnote',['uses'=> 'AdminProductController@editNotes','as' =>  'admin.service.noteEdit' ]);

    //NOTES ADD FOR USER
    Route::post('user/addnote',['uses'=> 'AdminUserController@addNotes','as' =>  'admin.service.user.note' ]);
    Route::post('user/editnote',['uses'=> 'AdminUserController@editNotes','as' =>  'admin.service.user.noteEdit' ]);

    //ROUTES FOR CONTACT
    Route::get('contact',['uses'=> 'AdminDashboardController@viewContact','as' =>  'admin.contact' ]);
    Route::get('contact/showData', ['uses' => 'AdminDashboardController@create','as' => 'admin.service.contact.create']);

    //PAGES ADD
    Route::get('pages', ['uses' => 'AdminPagesController@index','as' => 'admin.service.pages.index']);
    Route::get('pages/showData', ['uses' => 'AdminPagesController@create','as' => 'admin.service.pages.create']);
    Route::get('pages/add',['uses'=> 'AdminPagesController@add','as' =>  'admin.service.pages.add' ]);
    Route::post('pages/adddata',['uses'=> 'AdminPagesController@addData','as' =>  'admin.pages.add' ]);
    Route::get('pages/deletedata/{ID}',['uses'=> 'AdminPagesController@destroy','as' =>  'admin.service.pages.destroy' ]);
    Route::get('pages/edit/{ID}',['uses'=> 'AdminPagesController@edit','as' =>  'admin.service.pages.edit' ]);
    Route::post('pages/editdata',['uses'=> 'AdminPagesController@editData','as' =>  'admin.pages.edit' ]);


 //LANGUAGE ADD
    Route::get('language', ['uses' => 'AdminLanguageController@index','as' => 'admin.service.lang.index']);
    Route::get('language/showData', ['uses' => 'AdminLanguageController@create','as' => 'admin.service.lang.create']);
    Route::get('language/edit/{ID}',['uses'=> 'AdminLanguageController@edit','as' =>  'admin.service.lang.edit' ]);
    Route::post('language/editdata',['uses'=> 'AdminLanguageController@editData','as' =>  'admin.lang.edit' ]);
    Route::get('language/add',['uses'=> 'AdminLanguageController@add','as' =>  'admin.service.lang.add' ]);
    Route::post('language/adddata',['uses'=> 'AdminLanguageController@addData','as' =>  'admin.lang.add' ]);

    //FAQ ADD
    Route::get('faq', ['uses' => 'AdminFaqController@index','as' => 'admin.service.faq.index']);
    Route::get('faq/showData', ['uses' => 'AdminFaqController@create','as' => 'admin.service.faq.create']);
    Route::get('faq/add',['uses'=> 'AdminFaqController@add','as' =>  'admin.service.faq.add' ]);
    Route::post('faq/adddata',['uses'=> 'AdminFaqController@addData','as' =>  'admin.faq.add' ]);
    Route::get('faq/deletedata/{ID}',['uses'=> 'AdminFaqController@destroy','as' =>  'admin.service.faq.destroy' ]);
    Route::get('faq/edit/{ID}',['uses'=> 'AdminFaqController@edit','as' =>  'admin.service.faq.edit' ]);
    Route::post('faq/editdata',['uses'=> 'AdminFaqController@editData','as' =>  'admin.faq.edit' ]);


//TESTIMONAIL ADD
    Route::get('testimonial', ['uses' => 'AdminTestimonialController@index','as' => 'admin.service.testimonial.index']);
    Route::get('testimonial/showData', ['uses' => 'AdminTestimonialController@create','as' => 'admin.service.testimonial.create']);
    Route::get('testimonial/add',['uses'=> 'AdminTestimonialController@add','as' =>  'admin.service.testimonial.add' ]);
    Route::post('testimonial/adddata',['uses'=> 'AdminTestimonialController@addData','as' =>  'admin.testimonial.add' ]);
    Route::get('testimonial/deletedata/{ID}',['uses'=> 'AdminTestimonialController@destroy','as' =>  'admin.service.testimonial.destroy' ]);
    Route::get('testimonial/edit/{ID}',['uses'=> 'AdminTestimonialController@edit','as' =>  'admin.service.testimonial.edit' ]);
    Route::post('testimonial/editdata',['uses'=> 'AdminTestimonialController@editData','as' =>  'admin.testimonial.edit' ]);


  //BLOG ADD CATEGORY
    Route::get('blogcategory', ['uses' => 'AdminBlogCategoryController@index','as' => 'admin.service.blogcategory.index']);
    Route::get('blogcategory/showData', ['uses' => 'AdminBlogCategoryController@create','as' => 'admin.service.blogcategory.create']);
    Route::get('blogcategory/add',['uses'=> 'AdminBlogCategoryController@add','as' =>  'admin.service.blogcategory.add' ]);
    Route::post('blogcategory/adddata',['uses'=> 'AdminBlogCategoryController@addData','as' =>  'admin.blogcategory.add' ]);
    Route::get('blogcategory/deletedata/{ID}',['uses'=> 'AdminBlogCategoryController@destroy','as' =>  'admin.service.blogcategory.destroy' ]);
    Route::get('blogcategory/edit/{ID}',['uses'=> 'AdminBlogCategoryController@edit','as' =>  'admin.service.blogcategory.edit' ]);
    Route::post('blogcategory/editdata',['uses'=> 'AdminBlogCategoryController@editData','as' =>  'admin.blogcategory.edit' ]);

     //BLOG ADD
    Route::get('blog', ['uses' => 'AdminBlogController@index','as' => 'admin.service.blog.index']);
    Route::get('blog/showData', ['uses' => 'AdminBlogController@create','as' => 'admin.service.blog.create']);
    Route::get('blog/add',['uses'=> 'AdminBlogController@add','as' =>  'admin.service.blog.add' ]);
    Route::post('blog/adddata',['uses'=> 'AdminBlogController@addData','as' =>  'admin.blog.add' ]);
    Route::get('blog/deletedata/{ID}',['uses'=> 'AdminBlogController@destroy','as' =>  'admin.service.blog.destroy' ]);
    Route::get('blog/edit/{ID}',['uses'=> 'AdminBlogController@edit','as' =>  'admin.service.blog.edit' ]);
    Route::post('blog/editdata',['uses'=> 'AdminBlogController@editData','as' =>  'admin.blog.edit' ]);

     Route::post('service/engineer/action',['uses'=> 'AdminTestimonialController@addOrEdit','as' =>  'admin.service.company.addoredit' ]);
    
     });

   






