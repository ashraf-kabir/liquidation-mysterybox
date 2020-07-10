{
     "menus" : {
         "admin" : {
             "xyzPayments" : {
                "xyzDisputes" : "/stripe_disputes",
                "xyzInvoices" : "/stripe_invoices/0?order_by=id&direction=DESC",
                "xyzSubscriptions" : "/stripe_subscriptions/0?order_by=id&direction=DESC",
                "xyzProducts" : "/stripe_products/0?order_by=id&direction=DESC",
                "xyzPlans" : "/stripe_plans/0?order_by=id&direction=DESC",
                "xyzCoupons" : "/stripe_coupons/0?order_by=id&direction=DESC"
             }
         },
         "member" : {
             "xyzPayments" : {
                 "xyzCards" : "/stripe_cards"
             }
         }
     },
     "routes": {
         "v1/api/member/payment/user" : "Member/Payment_api_controller/new_user_payment",
         "v1/api/member/payment" : "Member/Payment_api_controller/payment",
         "v1/api/member/payment/mobile" : "Member/Payment_api_controller/mobile_payment",
         "v1/api/member/payment/user/mobile" : "Member/Payment_api_controller/user_mobile_payment",
         "v1/api/member/subscription" : "Member/Subscription_api_controller/subscribe_user",
         "v1/api/member/subscription/user" : "Member/Subscription_api_controller/user_subscription",
         "v1/api/member/subscription/mobile": "Member/Subscription_api_controller/mobile_user_subscription",
         "v1/api/member/subscription/user/mobile" : "Member/Subscription_api_controller/mobile_subscription",
         "v1/api/payment/user" : "Admin/Admin_payment_api_controller/new_user_payment",
         "v1/api/payment" : "Admin/Admin_payment_api_controller/payment",
         "v1/api/payment/mobile" : "Admin/Admin_payment_api_controller/mobile_payment",
         "v1/api/payment/user/mobile" : "Admin/Admin_payment_api_controller/user_mobile_payment",
         "admin/stripe_subscriptions/cancel/(:num)" : "Admin/Admin_stripe_subscriptions_controller/cancel/$1",
         "member/payment/user" : "Member/Payment_controller/new_user_payment",
         "member/payment" : "Member/Payment_controller/payment",
         "member/payment/mobile" : "Member/Payment_controller/mobile_payment",
         "member/payment/user/mobile" : "Member/Payment_controller/user_mobile_payment",
         "member/payment/test": "Member/Payment_controller/index",
         "member/subscription/test": "Member/Subscription_api_controller/index",
         "admin/stripe_payment_orders/refund/(:num)" : "Admin/Admin_stripe_payment_orders_controller/refund/$1",
         "admin/stripe_invoices/refund/(:any)" : "Admin/Admin_stripe_invoices_controller/refund_invoice/$1",
         "v1/api/stripe_events" : "Guest/Stripe_webhooks_api_controller/index"
     },
    "models" : [],
    "controllers" : [
       
        {
            "route": "/stripe_cards",
            "name": "stripe_cards",
            "page_name": "xyzCards",
            "model": "stripe_cards",
            "controller": "{{{portal}}}_stripe_cards_controller.php",
            "api_controller": "{{{portal}}}_api_stripe_cards_controller.php",
            "activity_log":false,
            "override_add": "",
            "override_edit": "",
            "override_view": "",
            "override_list": "",
            "api": false,
            "frontend": false,
            "paginate": true,
            "paginate_join": "",
            "portal": "{{{portal}}}",
            "is_crud": true,
            "method_edit_pre":"",
            "is_add": true,
            "is_edit": true,
            "is_delete": false,
            "is_real_delete": false,
            "is_list": true,
            "is_view": true,
            "import": false,
            "export": false,
            "is_filter": true,
            "all_records": false,
            "active_only": false,
            "method": "",
            "method_add": "",
            "method_edit": "",
            "method_list": "",
            "method_view": "",
            "method_add_success": "",
            "method_edit_success": "",
            "method_list_success": "",
            "method_view_success": "",
            "method_delete_success": "",
            "custom_view_add": "",
            "custom_view_edit": "",
            "custom_view_list": "",
            "custom_view_view": "",
            "listing_fields_api": ["id", "card_last", "card_brand", "card_exp_month", "exp_year", "card_name", "is_default"],
            "listing_headers": ["xyzID", "xyzLast 4 Digits", "xyzBrand", "xyzExpiry Month", "xyzExpiry Year", "xyzCard Name", "xyzDefault Card"],
            "listing_rows": ["id|integer", "card_last|string", "card_brand|string", "card_exp_month|string", "exp_year|string", "card_name|string", "is_default|integer" ],
            "listing_actions": [],
            "filter_fields": ["card_last","card_brand", "card_name", "is_default"],
            "add_fields": ["is_default"],
            "edit_fields": [ "is_default"],
            "view_fields": ["id", "card_last", "card_brand", "card_exp_month", "exp_year", "card_name", "is_default", "stripe_card_id","stripe_card_customer"],
            "autocomplete": "",
            "dynamic_mapping":{},
            "dynamic_mapping_add" : {},
            "load_libraries": []
        }
    ],
     "translations": {
     },
     "copy": {
        "../mkdcore/source/payment/src/Member_api_controller.php" : "../release/application/controllers/{{{portal}}}/{{{portal}}}_api_controller.php",
        "../mkdcore/source/payment/src/Stripe_cardsAdd.php" : "../release/application/views/{{{portal}}}/Stripe_cardsAdd.php",
        "../mkdcore/source/payment/src/{{{portal}}}_stripe_cards_controller.php" : "../release/application/controllers/{{{portal}}}/{{{portal}}}_stripe_cards_controller.php",
    
     }
}