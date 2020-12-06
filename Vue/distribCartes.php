<p>Le jeu peut maintenant commencer !</p>

<?= '<p class="mise">Mise : '.htmlspecialchars($_SESSION['mise']).'€</p>';?>

<table class="tableDistribCartes">
	<tr>
		<td>
			<p>La main du croupier :</p>
			<?= '<img src="../Images/53.png">';?>
			<?= '<img src="../Images/'.$_SESSION['carteC1'].'.png">';?>
			<?= '<p>Valeur : '.$_SESSION['valueC'].'</p>';?>
		</td>
		<td>
			<p>Votre main :</p>
			<?= '<img src="../Images/'.$_SESSION['carteP1'].'.png">';?>
			<?= '<img src="../Images/'.$_SESSION['carteP2'].'.png">';?>
			<?= '<p>Valeur : '.$_SESSION['valueP'].'</p>';?>
		</td>
	</tr>
</table>