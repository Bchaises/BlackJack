<div class="containerProfil">
	<div class="history">
		<table>
			<tr class="row_history">
				<th class="title_history">ID</th>
				<th class="title_history">Pari</th>
				<th class="title_history">Profit</th>
				<th class="title_history">Victoire/Défaite</th>
				<th class="title_history">Date</th>
			</tr>
			<?= 
				$parties
			?>

		</table>
	</div>

	<div id="userInfo">
		<div class="win_rate">
			<p>Pourcentage de victoire</p>
			<div class="circle">
				<?=$winRate?>
			</div>
		</div>

		<div class="solde">
			<p>Solde : <?=$_SESSION['money'];?>€</p>
		</div>
	</div>
</div>