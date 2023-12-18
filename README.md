<h1>Au Paradis d'Eve</h1>

<h2>Environnement de travail</h2>
<li>Symfony 6</li>
<li>Lancer un <b>composer install</b></li>
<li>MySQL</li>
<li>Installer <a href="https://scoop.sh/" target="_blank">Scoop sh</a></li>
<p> > Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser<br> > Invoke-RestMethod -Uri https://get.scoop.sh | Invoke-Expression</p>
<li>Installer Symfony CLI </li>
<p>Commande Windows : scoop install symfony-cli </p>

<h2>Bundles à installer</h2>
<li>MailHost</li>
<p>Télécharger exec de <a href="https://github.com/mailhog/MailHog/releases/tag/v1.0.1" target="_blank">MailHog</a> et l'exécuter (laisser tourner en arrière plan si besoin de tester les mails)</p>
<li>Dompdf</li>
<p>Lancer la commande : composer require dompdf/dompdf</p>

<h2>Base de données</h2>
<li>public/db.sql</li>
