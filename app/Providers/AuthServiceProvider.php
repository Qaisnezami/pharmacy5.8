<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('isUser',function($user) {
            foreach( $user->roles as $role)
            {
                if($role->name === 'Users')
                {
                    return  $role->name === 'Users';
                }
            }
            return false;
        });

        Gate::define('isCompany',function($user) {
            foreach( $user->roles as $role)
            {
                if($role->name === 'Company')
                {
                    return  $role->name === 'Company';
                }
            }
            return false;
        });

        Gate::define('isSupplier',function($user) {
            foreach( $user->roles as $role)
            {
                if($role->name === 'Suppliers')
                {
                    return  $role->name === 'Suppliers';
                }
            }
            return false;
        });



        Gate::define('isDoctor',function($user) {
            foreach( $user->roles as $role)
            {
                if($role->name === 'Doctors')
                {
                    return  $role->name === 'Doctors';
                }
            }
            return false;
        });

        Gate::define('isClassification',function($user) {
            foreach( $user->roles as $role)
            {
                if($role->name === 'Classification')
                {
                    return  $role->name === 'Classification';
                }
            }
            return false;
        });

        Gate::define('isManufacturer',function($user) {
            foreach( $user->roles as $role)
            {
                if($role->name === 'Manufacturer')
                {
                    return  $role->name === 'Manufacturer';
                }
            }
            return false;
        });

        Gate::define('isMedicineGroup',function($user) {
            foreach( $user->roles as $role)
            {
                if($role->name === 'Medicine Group')
                {
                    return  $role->name === 'Medicine Group';
                }
            }
            return false;
        });
        Gate::define('isPurchase',function($user) {
            foreach( $user->roles as $role)
            {
                if($role->name === 'Purchase')
                {
                    return  $role->name === 'Purchase';
                }
            }
            return false;
        });

        Gate::define('isPurchaseReport',function($user) {
            foreach( $user->roles as $role)
            {
                if($role->name === 'Purchase Report')
                {
                    return  $role->name === 'Purchase Report';
                }
            }
            return false;
        });

        Gate::define('isMedicines',function($user) {
            foreach( $user->roles as $role)
            {
                if($role->name === 'Add Medicines')
                {
                    return  $role->name === 'Add Medicines';
                }
            }
            return false;
        });
        Gate::define('isCounterSales',function($user) {
            foreach( $user->roles as $role)
            {
                if($role->name === 'Counter Sales')
                {
                    return  $role->name === 'Counter Sales';
                }
            }
            return false;
        });
        Gate::define('isCounterSalesReport',function($user) {
            foreach( $user->roles as $role)
            {
                if($role->name === 'Counter Sales Report')
                {
                    return  $role->name === 'Counter Sales Report';
                }
            }
            return false;
        });

        Gate::define('isLowStock',function($user) {
            foreach( $user->roles as $role)
            {
                if($role->name === 'Low Stock')
                {
                    return  $role->name === 'Low Stock';
                }
            }
            return false;
        });

        Gate::define('isExpiry',function($user) {
            foreach( $user->roles as $role)
            {
                if($role->name === 'Item Expiry Date')
                {
                    return  $role->name === 'Item Expiry Date';
                }
            }
            return false;
        });
        Gate::define('isInventoryReport',function($user) {
            foreach( $user->roles as $role)
            {
                if($role->name === 'Inventory Reports')
                {
                    return  $role->name === 'Inventory Reports';
                }
            }
            return false;
        });

        Gate::define('isExpenseCategory',function($user) {
            foreach( $user->roles as $role)
            {
                if($role->name === 'Expenses Category')
                {
                    return  $role->name === 'Expenses Category';
                }
            }
            return false;
        });
        Gate::define('isExpense',function($user) {
            foreach( $user->roles as $role)
            {
                if($role->name === 'Expenses')
                {
                    return  $role->name === 'Expenses';
                }
            }
            return false;
        });
        Gate::define('isProfile',function($user) {
            foreach( $user->roles as $role)
            {
                if($role->name === 'Profile')
                {
                    return  $role->name === 'Profile';
                }
            }
            return false;
        });
        Gate::define('isSetting',function($user) {
            foreach( $user->roles as $role)
            {
                if($role->name === 'Setting')
                {
                    return  $role->name === 'Setting';
                }
            }
            return false;
        });
        Gate::define('isDashboard',function($user) {
            foreach( $user->roles as $role)
            {
                if($role->name === 'Dashboard')
                {
                    return  $role->name === 'Dashboard';
                }
            }
            return false;
        });
        Gate::define('isBarcode',function($user) {
            foreach( $user->roles as $role)
            {
                if($role->name === 'Barcode')
                {
                    return  $role->name === 'Barcode';
                }
            }
            return false;
        });
        Gate::define('isOrder',function($user) {
            foreach( $user->roles as $role)
            {
                if($role->name === 'Order')
                {
                    return  $role->name === 'Order';
                }
            }
            return false;
        });
        Gate::define('isOrderReport',function($user) {
            foreach( $user->roles as $role)
            {
                if($role->name === 'Order Report')
                {
                    return  $role->name === 'Order Report';
                }
            }
            return false;
        });

        Gate::define('Read',function($user) {
            foreach( $user->permissions as $permission)
            {
                if($permission->name === 'Read')
                {
                    return  $permission->name === 'Read';
                }
            }
            return false;
        });
        Gate::define('Create',function($user) {
            foreach( $user->permissions as $permission)
            {
                if($permission->name === 'Create')
                {
                    return  $permission->name === 'Create';
                }
            }
            return false;
        });
        Gate::define('Update',function($user) {
            foreach( $user->permissions as $permission)
            {
                if($permission->name === 'Update')
                {
                    return  $permission->name === 'Update';
                }
            }
            return false;
        });
        
        Gate::define('Delete',function($user) {
            foreach( $user->permissions as $permission)
            {
                if($permission->name === 'Delete')
                {
                    return  $permission->name === 'Delete';
                }
            }
            return false;
        });

        Gate::define('Approval',function($user) {
            foreach( $user->permissions as $permission)
            {
                if($permission->name === 'Approval')
                {
                    return  $permission->name === 'Approval';
                }
            }
            return false;
        });
        Gate::define('Activation',function($user) {
            foreach( $user->permissions as $permission)
            {
                if($permission->name === 'Activation')
                {
                    return  $permission->name === 'Activation';
                }
            }
            return false;
        });

        Passport::routes();
    }
}
