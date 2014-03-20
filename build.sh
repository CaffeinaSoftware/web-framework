find src -name \*.java -print > file.list

javac -d bin @file.list || exit;

rm file.list

# Construir orm-client jar
echo "Main-Class: mx.caffeina.webframework.orm.client.Main" > manifest

cd bin

if [ ! -d ../dist ]; then
	mkdir ../dist
fi

jar cfm ../dist/orm-client.jar ../manifest mx

cd ..

rm manifest
rm -rf bin/mx

