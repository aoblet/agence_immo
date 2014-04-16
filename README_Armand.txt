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