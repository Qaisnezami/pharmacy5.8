export default class Gate{


        constructor(user)
        {
            this.user = user;
        }

        isUser(){

            for (let index = 0; index < user.length; index++) {
                if(user[index].name ==='Users'){
                    return user[index].name ==='Users';
                }
            }
            return false;
        }

        isCompany(){

            for (let index = 0; index < user.length; index++) {
                if(user[index].name ==='Company'){
                    return user[index].name ==='Company';
                }
            }
            return false;
        }

       

        isSupplier(){

            for (let index = 0; index < user.length; index++) {
                if(user[index].name ==='Suppliers'){
                    return user[index].name ==='Suppliers';
                }
            }
            return false;
        }

        isDoctor(){
            for (let index = 0; index < user.length; index++) {
                if(user[index].name ==='Doctors'){
                    return user[index].name ==='Doctors';
                }
            }
            return false;
        }

        isClassification(){
            for (let index = 0; index < user.length; index++) {
                if(user[index].name ==='Classification'){
                    return user[index].name ==='Classification';
                }
            }
            return false;
        }

        isManufacturer(){
            for (let index = 0; index < user.length; index++) {
                if(user[index].name ==='Manufacturer'){
                    return user[index].name ==='Manufacturer';
                }
            }
            return false;
        }


        isMedicineGroup(){
            for (let index = 0; index < user.length; index++) {
                if(user[index].name ==='Medicine Group'){
                    return user[index].name ==='Medicine Group';
                }
            }
            return false;
        }


        isMedicines(){
            for (let index = 0; index < user.length; index++) {
                if(user[index].name ==='Add Medicines'){
                    return user[index].name ==='Add Medicines';
                }
            }
            return false;
        }

        
        isPurchase(){
            for (let index = 0; index < user.length; index++) {
                if(user[index].name ==='Purchase'){
                    return user[index].name ==='Purchase';
                }
            }
            return false;
        }

        isPurchaseReport(){
            for (let index = 0; index < user.length; index++) {
                if(user[index].name ==='Purchase Report'){
                    return user[index].name ==='Purchase Report';
                }
            }
            return false;
        }

        isCounterSales(){
            for (let index = 0; index < user.length; index++) {
                if(user[index].name ==='Counter Sales'){
                    return user[index].name ==='Counter Sales';
                }
            }
            return false;
        }

        isCounterSalesReport(){
            for (let index = 0; index < user.length; index++) {
                if(user[index].name ==='Counter Sales Report'){
                    return user[index].name ==='Counter Sales Report';
                }
            }
            return false;
        }
        isLowStock(){
            for (let index = 0; index < user.length; index++) {
                if(user[index].name ==='Low Stock'){
                    return user[index].name ==='Low Stock';
                }
            }
            return false;
        }

        isExpiry(){
            for (let index = 0; index < user.length; index++) {
                if(user[index].name ==='Item Expiry Date'){
                    return user[index].name ==='Item Expiry Date';
                }
            }
            return false;
        }
        
        isInventoryReport(){
            for (let index = 0; index < user.length; index++) {
                if(user[index].name ==='Inventory Reports'){
                    return user[index].name ==='Inventory Reports';
                }
            }
            return false;
        }
        
        isExpenseCategory(){
            for (let index = 0; index < user.length; index++) {
                if(user[index].name ==='Expenses Category'){
                    return user[index].name ==='Expenses Category';
                }
            }
            return false;
        }

        isExpense(){
            for (let index = 0; index < user.length; index++) {
                if(user[index].name ==='Expenses'){
                    return user[index].name ==='Expenses';
                }
            }
            return false;
        }
        isProfile(){
            for (let index = 0; index < user.length; index++) {
                if(user[index].name ==='Profile'){
                    return user[index].name ==='Profile';
                }
            }
            return false;
        }
        isSetting(){
            for (let index = 0; index < user.length; index++) {
                if(user[index].name ==='Setting'){
                    return user[index].name ==='Setting';
                }
            }
            return false;
        }
        isDashboard(){
            for (let index = 0; index < user.length; index++) {
                if(user[index].name ==='Dashboard'){
                    return user[index].name ==='Dashboard';
                }
            }
            return false;
        }
        isBarcode(){
            for (let index = 0; index < user.length; index++) {
                if(user[index].name ==='Barcode'){
                    return user[index].name ==='Barcode';
                }
            }
            return false;
        }
        isOrder(){
            for (let index = 0; index < user.length; index++) {
                if(user[index].name ==='Order'){
                    return user[index].name ==='Order';
                }
            }
            return false;
        }

        isOrderReport(){
            for (let index = 0; index < user.length; index++) {
                if(user[index].name ==='Order Report'){
                    return user[index].name ==='Order Report';
                }
            }
            return false;
        }

        Read(){
            for (let index = 0; index < permissions.length; index++) {
                if(permissions[index].name ==='Read'){
                    return permissions[index].name ==='Read';
                }
            }
            return false;
        }
        Create(){
            for (let index = 0; index < permissions.length; index++) {
                if(permissions[index].name ==='Create'){
                    return permissions[index].name ==='Create';
                }
            }
            return false;
        }
        Delete(){
            for (let index = 0; index < permissions.length; index++) {
                if(permissions[index].name ==='Delete'){
                    return permissions[index].name ==='Delete';
                }
            }
            return false;
        }
        Update(){
            for (let index = 0; index < permissions.length; index++) {
                if(permissions[index].name ==='Update'){
                    return permissions[index].name ==='Update';
                }
            }
            return false;
        }

        Activation(){
            for (let index = 0; index < permissions.length; index++) {
                if(permissions[index].name ==='Activation'){
                    return permissions[index].name ==='Activation';
                }
            }
            return false;
        }

        Approval(){
            for (let index = 0; index < permissions.length; index++) {
                if(permissions[index].name ==='Approval'){
                    return permissions[index].name ==='Approval';
                }
            }
            return false;
        }



}