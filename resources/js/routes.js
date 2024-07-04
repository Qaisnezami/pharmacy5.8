export default[
    
    { path: '/dashboard', component: require('./components/Dashboard.vue').default },
    { path: '/company', component: require('./components/Company.vue').default },
    { path: '/users', component: require('./components/Users.vue').default },
    { path: '/supplier', component: require('./components/Supplier.vue').default },
    { path: '/supplier/:id', component: require('./components/SupplierPayments.vue').default },
    { path: '/doctor', component: require('./components/Doctor.vue').default },
    { path: '/classification', component: require('./components/Classification.vue').default },
    { path: '/manufacturer', component: require('./components/Manufacturer.vue').default },
    { path: '/groups', component: require('./components/Group.vue').default },
    { path: '/medicine', component: require('./components/Medicine.vue').default },
    { path: '/barcode', component: require('./components/Barcode.vue').default },

    { path: '/profile', component: require('./components/Profile.vue').default },

    { path: '/purchase', component: require('./components/Purchase/index.vue').default },
    { path: '/purchase/create', component: require('./components/Purchase/create.vue').default },
    { path: '/purchase/show/:id', component: require('./components/Purchase/show.vue').default },
    { path: '/purchase/edit/:id', component: require('./components/Purchase/edit.vue').default },
    { path: '/purchase/report', component: require('./components/Purchase/Report.vue').default },


    { path: '/counter-sale', component: require('./components/CounterSales/index.vue').default },
    { path: '/counter-sale/create', component: require('./components/CounterSales/create.vue').default },
    { path: '/counter-sale/show/:id', component: require('./components/CounterSales/show.vue').default },
    // { path: '/counter-sale/edit/:id', component: require('./components/CounterSales/edit.vue').default },
    { path: '/counter-sale/return/:id', component: require('./components/CounterSales/return.vue').default },
    { path: '/counter-sale/report', component: require('./components/CounterSales/Report.vue').default },
    // { path: '/counter-sale/report/print', component: require('./components/CounterSales/ReportPrint.vue').default },


    { path: '/expenses-category', component: require('./components/Expenses/Expense_category.vue').default },
    { path: '/income', component: require('./components/Expenses/Income.vue').default },
    { path: '/income/report', component: require('./components/Expenses/Report.vue').default },
    { path: '/expenses/:id', component: require('./components/Expenses/Expense.vue').default },

    { path: '/stock/low', component: require('./components/Stocks/Low.vue').default },
    { path: '/stock/low/details/:id', component: require('./components/Stocks/LowDetails.vue').default },
    { path: '/stock/expiry', component: require('./components/Stocks/Expiry.vue').default },
    { path: '/stock/report', component: require('./components/Stocks/Report.vue').default },
    { path: '/setting', component: require('./components/Setting.vue').default },



    { path: '/order', component: require('./components/Orders/index.vue').default },
    { path: '/order/create', component: require('./components/Orders/create.vue').default },
    { path: '/order/show/:id', component: require('./components/Orders/show.vue').default },
    { path: '/order/edit/:id', component: require('./components/Orders/edit.vue').default },
    { path: '/order/report', component: require('./components/Orders/Report.vue').default },





    { path: '/*', component: require('./components/NotFound.vue').default },

]
