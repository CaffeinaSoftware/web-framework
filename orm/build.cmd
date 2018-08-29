@echo off

mkdir bin

dir /s/b src\*.java  > file.list

javac -source 1.7 -target 1.7  -d bin @file.list || goto eos

del file.list

echo Main-Class: mx.caffeina.webframework.orm.client.Main > manifest
cd bin
mkdir ..\dist
jar cfm ../dist/orm-client.jar ../manifest mx
cd ..
del manifest
rd /s/q bin\mx

:eos

