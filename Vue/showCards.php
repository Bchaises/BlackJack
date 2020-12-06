<p>Le jeu peut maintenant commencer !</p>

<?= '<p class="mise">Mise : '.htmlspecialchars($_SESSION['mise']).'€</p>';?>

<table class="tableDistribCartes">
	<tr>
		<td>
			<p>La main du croupier :</p>
			<?= $affichageC;?>
			<?= '<p>Valeur : '.$_SESSION['valueC'].'</p>';?>
		</td>
		<td>
			<p>Votre main :</p>
			<?= $affichageP;?>
			<?= '<p>Valeur : '.$_SESSION['valueP'].'</p>';?>
		</td>
	</tr>
</table>