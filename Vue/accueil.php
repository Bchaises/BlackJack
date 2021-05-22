<div id="container_accueil">

	<div id="banniere">
		<img src="../Images/big_blackjack_desktop.jpg">
		<h1>LE BLACKJACK</h1>
	</div>

	<div id="accueil_details">
		
		<div id="accueil_item1">
			<h4>Quel est le but de ce site ?</h4>
			<p>Ce site web a été réalisé dans l'objectif de le présenter pendant l'épreuve E4 du BTS SIO(Services Informatiques aux Organisations) option SLAM(spécialisé dans le développement d'applications). Je vous invite à visiter le site web en vous créant un compte ou en vous connectant directement. Si vous voulez accéder à mon Portfolio dirigez vous <span><a href="https://www.benjamin-chaises.fr">ici</a></span>, où vous pourrez me contacter dans l'onglet "Me contacter", si vous avez des questions.</p>
		</div>

		<div id="accueil_item2">
			<h4>Que faire sur le site ?</h4>
			<p>Vous pourrez jouer au Blackjack en vous inscrivant grâce au boutton "Connexion".Le but est de battre le croupier en suivant les règles de base, pour plus d'informations dirigez vous sur l'onglet "Règles". Attention, lorsque vous vous créez un compte votre solde sera par défaut à 1000€ en sachant que l'argent sur ce site est factice.S'il déscend à 0€ votre compte sera intégralement supprimer. Pour suivre votre progression vous aurez à votre disposition l'onglet "Profil".<br><br> Parfait ! Maintenant vous êtes paré pour être millionaire !</p>
		</div>

	</div>

	<div id="container_classement">
		<div id="title">
			<h4>Classements des 10 meilleurs joueurs</h4>
		</div>

		<div id="table_classement">
			<table id="classement">
				<tr>
					<th>Rang</th>
					<th>Pseudo</th>
					<th>Solde</th>
					<th>Pourcentage de Vitoire</th>
				</tr>
				<?= 

						$classement 

				?>
			</table>
		</div>
	</div>
	
</div>