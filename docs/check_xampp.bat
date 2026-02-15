@echo off
echo ================================================
echo   XAMPP Service Checker
echo   Indian NGO Website
echo ================================================
echo.

echo Checking XAMPP Services...
echo.

REM Check if Apache is running
tasklist /FI "IMAGENAME eq httpd.exe" 2>NUL | find /I /N "httpd.exe">NUL
if "%ERRORLEVEL%"=="0" (
    echo [OK] Apache is RUNNING
) else (
    echo [ERROR] Apache is NOT RUNNING
    echo         Fix: Open XAMPP Control Panel and click START for Apache
)

echo.

REM Check if MySQL is running
tasklist /FI "IMAGENAME eq mysqld.exe" 2>NUL | find /I /N "mysqld.exe">NUL
if "%ERRORLEVEL%"=="0" (
    echo [OK] MySQL is RUNNING
) else (
    echo [ERROR] MySQL is NOT RUNNING
    echo         Fix: Open XAMPP Control Panel and click START for MySQL
)

echo.
echo ================================================
echo.

REM Check if XAMPP is installed in common locations
if exist "C:\xampp\xampp-control.exe" (
    echo XAMPP Found: C:\xampp\
    echo.
    set /p OPEN="Open XAMPP Control Panel? (Y/N): "
    if /i "%OPEN%"=="Y" (
        start "" "C:\xampp\xampp-control.exe"
    )
) else (
    echo XAMPP not found in C:\xampp\
    echo Please start XAMPP Control Panel manually
)

echo.
echo After starting services, visit:
echo http://localhost/IndianNGOWebsite/IndianNGOWebsite/test_connection.php
echo.
pause
