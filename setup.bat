@echo off
call npm install
attrib node_modules +h
call bower install
attrib bower_components +h
call gulp deps
call gulp less
@echo on