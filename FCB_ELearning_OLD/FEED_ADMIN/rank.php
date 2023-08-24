<?php 
  use classes\UserQuiz;
  use classes\number;
  use classes\url;
  require_once "../___autoload.php";
  $u = new UserQuiz();
  $id = url::get("id");
  $data = [];
  if ($id != "null") {
    $data = $u->getRankByQuiz($id);
  }
  $data_len = count($data);
?>
<?php if ($data_len > 0): ?>
<div style="margin-top:10px;width:100%;min-height:200px;background-color:white;padding:5px;border-radius:10px;box-shadow:3px 3px 3px rgba(0,0,0,0.3);">
  <h3>Ranking (Top 10)</h3>
  <table class="table table-sm">
    <tr>
      <td>Rank</td>
      <td>Name</td>
      <td>Branch</td>
      <td>Score</td>
      <td>Percent</td>
      <td><i class="fa fa-cog"></i></td>     
    </tr>
    <?php 
        $label = [];
        $value = [];
        for ($i = 0; $i < $data_len; $i++): 
            $style = "background-color:brown;color:white;";
            $rank = $data[$i]["rank_no"];
            if ($rank == 1)
              $style = "background-color:gold;color:white;";
            else if ($rank == 2)
              $style = "background-color:silver;color:white;";
            else if ($rank == 3)
              $style = "background-color:#c56900;color:white;";
          $label[] = "'".$data[$i]["name"]."'";
          $value[] = $data[$i]["score"];
    ?>
      <tr>
        <td>
          <div style="<?php echo $style; ?>border-radius:50%;padding:10px;height:45px;width:45px;font-weight:bold;">
            <?php
             if ($rank > 0) {
                echo number::ordinal((int)$rank);
             }
            ?>
          </div>
        </td>
        <td><?php echo $data[$i]["name"]; ?></td>
        <td><?php echo $data[$i]["branch"]; ?></td>
        <td><?php echo $data[$i]["score"]."/".$data[$i]["total_score"]; ?></td>
        <td><?php echo number_format(round($data[$i]["score"] / $data[$i]["total_score"] * 100, 2), 2).'%'; ?></td>
        <td style="width:120px;">
          <button class="view-profile btn btn-info btn-primary btn-sm" rel="<?php echo $data[$i]["uid"];?>">
            <i class="fa fa-search"></i> View Profile
          </button>
        </td>
      </tr>
    <?php endfor; ?>
  </table>
  <div style="width:500px;height:500px;margin:auto;margin-top:20px;">
    <canvas id="myChart"></canvas>
  </div>
</div>
<?php 
  $label = implode(",", $label);
  $value = implode(",", $value);
?>
<script>
try {
  ctx, myChart = undefined;
  $(".view-profile").off("click");
} catch (er) {console.log(er.toString());}
var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
  type: 'pie',
  data: {
    labels: [<?php echo $label; ?>],
    datasets: [{
      label: 'Top',
      data: [<?php echo $value; ?>],
      backgroundColor: [
        'rgba(255, 99, 132, 1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)'
      ],
      borderColor: [
        'rgba(255, 99, 132, 1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)'
      ],
      borderWidth: 0
    }]
  },
  options: {
    scales: {
      y: {
          beginAtZero: false
      }
    }
  }
});
$(".view-profile").on("click", function() {
  var id = $(this).attr("rel");
  var r_id = $("#rankid").val();
  mainroute("<?php echo ADMIN_PATH; ?>stat_view.php?uid=" + id + "&prev=<?php echo ADMIN_PATH;  ?>rank_page.php?id=" + r_id);
});
</script>
<?php else: ?>
  <div style="padding:20px;color:red;">
    No Data!
  </div>
<?php endif; ?>