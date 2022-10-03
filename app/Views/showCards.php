<div id="infoUser">
	<p>Le jeu peut maintenant commencer !</p>

	<div id="infoMoney">
		<p class="mise" style="padding-right: 10px;border-right:1px solid black">Mise :<span style="font-weight: bold;"> <?=$_SESSION['mise']?>€</span></p>
		<p class="mise" style="padding-left: 10px;">Solde :<span style="font-weight: bold;"> <?=$_SESSION['money']?>€</span></p>
	</div>
</div>

<div id="tableDistribCartes">

	<div id="croupierHand">
		<p>La main du croupier :</p>
		<?= $affichageC;?>
		<p>Valeur : <?=$_SESSION['valueC']?></p>
	</div>

	<div id="playerHand">
		<p>Votre main :</p>
		<?= $affichageP;?>
		<p>Valeur : <?=$_SESSION['valueP']?></p>
	</div>
</div>