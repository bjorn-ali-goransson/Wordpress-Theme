@echo off
call npm install
attrib node_modules +h
call brunch watch
@echo on