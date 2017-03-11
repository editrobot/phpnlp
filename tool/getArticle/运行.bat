@echo off
path=C:\Program Files (x86)\wamp\bin\php\php5.3.13;
COLOR f0
php wi.php
:main_loop
	php 123.php
	pause
goto main_loop