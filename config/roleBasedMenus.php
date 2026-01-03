<?php

class RoleBasedMenus
{
    public static function render(int $role_id, string $base_url,string $activePath,string $type)
    {
        // check active menu
        $isActive = function ($path) use ($activePath, $type) {

                if ($type == 'web') {
                    return ($activePath == $path) ? 'active' : '';
                }

                if ($type == 'mobile') {
                    return ($activePath == $path) ? 'menu-item active' : 'menu-item';
                }

                return '';
        };


        switch ($role_id) {

            case 1: // candidate
                echo '<a class="' . $isActive('home.php') . '" href="' . $base_url . 'home.php">Home</a>';
                echo '<a class="' . $isActive('job-search.php') . '" href="' . $base_url . 'job-applicant/job-search.php">Find A Job</a>';
                echo '<a class="' . $isActive('my-jobs.php') . '" href="' . $base_url . 'job-applicant/my-jobs.php">My Jobs</a>';
                echo '<a class="' . $isActive('help.php') . '" href="' . $base_url . 'help.php">Help</a>';
                break;

            case 2: // employer
                echo '<a class="' . $isActive('dashboard.php') . '" href="' . $base_url . 'Employer/dashboard.php">Dashboard</a>';
                echo '<a class="' . $isActive('job_list.php') . '" href="' . $base_url . 'Jobs/job_list.php">Job Posts</a>';
                echo '<a class="' . $isActive('application-overview.php') . '" href="' . $base_url . 'job-applicant/application-overview.php">Applications</a>';
                echo '<a class="' . $isActive('company_profile.php') . '" href="' . $base_url . 'Employer/company_profile.php">Company</a>';
                echo '<a class="' . $isActive('help.php') . '" href="' . $base_url . 'help.php">Help</a>';
                break;

            case 3: // admin
                echo '<a class="' . $isActive('dashboard.php') . '" href="' . $base_url . 'Admin/dashboard.php">Dashboard</a>';
                echo '<a class="' . $isActive('admin_user_list.php') . '" href="' . $base_url . 'user-management/admin_user_list.php">Users</a>';
                echo '<a class="' . $isActive('employer_verification.php') . '" href="' . $base_url . 'Admin/employer_verification.php">Companies</a>';
                echo '<a class="' . $isActive('help.php') . '" href="' . $base_url . 'help.php">Help</a>';
                break;

            default: // guest
                echo '<a class="' . $isActive('home.php') . '" href="' . $base_url . 'home.php">Home</a>';
                echo '<a class="' . $isActive('job-search.php') . '" href="' . $base_url . 'job-applicant/job-search.php">Find A Job</a>';
                echo '<a class="' . $isActive('help.php') . '" href="' . $base_url . 'help.php">Help</a>';
                break;
        }
    }
}

?>
