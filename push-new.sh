#!/bin/bash 

cd ../qc-new/

git checkout dev
git pull origin dev

git checkout qc
git merge dev 

git pull qc-new qc 
git push qc-new qc

git checkout dev

echo "Finish New HTR APIS\n"
echo "################################\n\n"

