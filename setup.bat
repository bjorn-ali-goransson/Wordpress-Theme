@echo off
call npm install
call bower install
call gulp deps
call gulp less
@echo on