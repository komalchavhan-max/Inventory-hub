<?php

namespace App\Helpers;

class TitleHelper
{

    public static function getTitle(){
        $routeName = request()->route()->getName();
        
        if (!$routeName) {
            return 'Dashboard';
        }
        
        $cleanName = preg_replace('/^(admin|employee)\./', '', $routeName);     
        
        $parts = explode('.', $cleanName);
        
        $titles = [
            'admin.dashboard' => 'Dashboard',        // Admin routes
            'admin.equipment.index' => 'Equipment List',
            'admin.equipment.create' => 'Add Equipment',
            'admin.equipment.edit' => 'Edit Equipment',
            'admin.equipment.show' => 'Equipment Details',
            'admin.categories.index' => 'Categories',
            'admin.categories.create' => 'Add Category',
            'admin.categories.edit' => 'Edit Category',
            'admin.categories.show' => 'Category Details',
            'admin.requests.equipment' => 'Equipment Requests',
            'admin.requests.exchange' => 'Exchange Requests',
            'admin.requests.repair' => 'Repair Requests',
            'admin.requests.return' => 'Return Requests',
            'admin.maintenance-logs.index' => 'Maintenance Logs',
            'admin.maintenance-logs.create' => 'Add Maintenance Log',
            'admin.maintenance-logs.show' => 'Maintenance Log Details',
            'admin.roles.index' => 'Roles Management',
            
            'employee.dashboard' => 'My Dashboard',     // Employee routes
            'employee.requests.equipment.form' => 'Request Equipment',
            'employee.requests.exchange.form' => 'Exchange Equipment',
            'employee.requests.repair.form' => 'Report Repair',
            'employee.requests.return.form' => 'Return Equipment',
            'employee.my-requests' => 'My Requests',
            'employee.notifications.index' => 'Notifications',
            
            'login' => 'Login',    // Auth routes
            'register' => 'Register',
            'password.request' => 'Forgot Password',
            'password.reset' => 'Reset Password',
            'password.confirm' => 'Confirm Password',
            'verification.notice' => 'Verify Email',
        ];
  
        if (isset($titles[$routeName])) {
            return $titles[$routeName];
        }
    
        $lastPart = end($parts);
        return ucfirst(str_replace(['-', '_'], ' ', $lastPart));
    }

    public static function getPanel(){       //panel name (Admin/Employee)
        if (request()->route()) {
            $routeName = request()->route()->getName();
            if (strpos($routeName, 'admin.') === 0) {
                return 'Admin Panel';
            }
            if (strpos($routeName, 'employee.') === 0) {
                return 'Employee Portal';
            }
        }
        return '';
    }
}