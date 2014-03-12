@echo off

dir /s/b src\*.java  > file.list

javac -d bin @file.list || goto eos

del file.list

echo Main-Class: mx.caffeina.webframework.orm.client.Main > manifest
cd bin
mkdir ..\dist
jar cfm ../dist/orm-client.jar ../manifest mx
cd ..
del manifest
rd /s/q bin\mx

:eos

