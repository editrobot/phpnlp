@echo off
path=C:\Program Files (x86)\wamp\bin\php\php5.3.13;
COLOR f0
php tool_wi.php
:main_loop
	php tool_index.php
	php tool_make_word_Article_index.php
	pause
goto main_loop