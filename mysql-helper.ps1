# PowerShell script to execute MySQL commands without password warnings
# Usage: .\mysql-helper.ps1 "SELECT * FROM users LIMIT 1;"

param(
    [Parameter(Mandatory=$true)]
    [string]$SqlCommand
)

# Execute the command with MYSQL_PWD environment variable
docker exec -e MYSQL_PWD=root_password -i suitecrm_mysql mysql -u root suitecrm -e $SqlCommand 