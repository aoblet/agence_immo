readme armand:
supprime ton dossier agence immo de ton pc et de github

va faire un tour sur trygit depuis google : cest vachement cool!

git clone git://github.com/aoblet/agence_immo.git
-> nouveau dossier sur ton pc
-> ton serveur est config sous le nom de origin (.git/config)

Justement il faut le modifier et mettre https au lieu de git://

cd .git/ && nano config
=> tu remplace git:// par https:// et voila :)
Normalement on est près a bosser ensemble. Je te conseille vivement trygit

git add fichier : track new file
git commit -m "message" : valide modif et tout
git push -u origin master : envoie sur le serveur les modif, le -u te permet par la suite de faire seulement git push ;)
git pull : recup le projet (mes modifs et tout)

après si tu as fait une suppression sans faire exprès:
va sur github recup les 4 premiers caractères du code du commit et fait
git revert 4premierscaractères

si t'as un pb dis le moi. 
.
