readme armand git:
supprime ton dossier agence immo de ton pc et de github

va faire un tour sur trygit depuis google : cest vachement cool!


/** INIT AGENCE_IMMO **/
git clone git://github.com/aoblet/agence_immo.git
-> ton serveur distant est config sous le nom de origin (.git/config)
il faut que tu changes : git en https:
cd agence_immo
nano .git/config -> tu change au remote


/** COMMANDES **/
git status : voir l'état de ton dépot git
git add fichier : track new file
git commit -m "message" : valide modif et tout
git push -u origin master : envoie sur le serveur les modif, le -u te permet par la suite de faire seulement git push ;)
git pull origin master: recup le projet (mes modifs et tout)

en fait le processus est le suivant : 
- tu fais tes modifs en local
- quand t'es satisfait: git add fichiers ou rep
- git commit -m "message"
- git push origin master  (premiere fois git push -u origin master)

après si tu as fait une suppression sans faire exprès:
va sur github recup les 4 premiers caractères du code du commit et fait
git revert 4premierscaractères

si t'as un pb dis le moi :) 

