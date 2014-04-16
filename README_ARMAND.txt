readme armand git:
supprime ton dossier agence immo de ton pc et de github

va faire un tour sur trygit depuis google : cest vachement cool!

creer new dossier : agence_immo
va dedans
git init
git remote add origin git://github.com/aoblet/agence_immo.git
-> ton serveur distant est config sous le nom de origin (.git/config)

git status : voir l'état de ton dépot git
git add fichier : track new file
git commit -m "message" : valide modif et tout
git push -u origin master : envoie sur le serveur les modif, le -u te permet par la suite de faire seulement git push ;)
git pull : recup le projet (mes modifs et tout)

en fait le processus est le suivant : 
- tu fais tes modifs en local
- quand t'es satisfait: git add fichiers ou rep
- git commit -m "message"
- git push origin (premiere fois git push -u origin master)

après si tu as fait une suppression sans faire exprès:
va sur github recup les 4 premiers caractères du code du commit et fait
git revert 4premierscaractères

si t'as un pb dis le moi :)
