#!/bin/bash 

cd ../qc/

git checkout dev
git pull origin dev

git checkout qc
git merge dev 

ssh-add ~/.ssh/htr/key 

git pull qc qc 
git push qc qc

#git push qc-new qc

git checkout dev

sh ./push-new.sh

echo "################################"
echo "Finished successfully"
