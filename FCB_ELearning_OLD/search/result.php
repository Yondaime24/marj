<?php 
  use classes\url;
  use classes\Date as Date2;
  require_once '../___autoload.php';
  $search = trim(url::post("search"));
  if (empty($search)) {
    print json_encode([]);
    exit;
  }

  $search = "%".$search."%";
  // searching for the topics
  $st = sql::con1()->prepare("select 
    top 100
    st.id as sub_id,
    st.title as sub_title,
    st.createdon as sub_dt,
    t.id as main_id,
    t.title as main_title,
    t.createdon as main_dt,
    tc.id as cat_id,
    tc.title as cat_title,
    tc.createdon as cat_dt
    from sub_topics as st 
    inner join topics as t on st.topic_id = t.id
    inner join topics_category as tc on tc.id = t.catid
    where (t.status = '1' and st.status = '1') and 
    (
    st.title like :st_title or
    t.title like :t_title or
    tc.title like :tc_title
    ) order by st.createdon desc
  ");
  $st->bindParam(":st_title", $search);
  $st->bindParam(":t_title", $search);
  $st->bindParam(":tc_title", $search);
  $st->execute();
  $topic = $st->fetchAll();
  $topic_len = count($topic);
  $search_data = [];

  
  $j = 0;
  for ($i = 0; $i < $topic_len; $i++) {
    $tarr = [];
    $tarr["main_id"] = htmlspecialchars($topic[$i]["main_id"]);
    $tarr["main_title"] = htmlspecialchars($topic[$i]["main_title"]);
    $tarr["main_dt"] = htmlspecialchars($topic[$i]["main_dt"]);
    $tarr["main_dt_ago"] = Date2::Ago($topic[$i]["main_dt"]);
    $tarr["sub_id"] = htmlspecialchars($topic[$i]["sub_id"]);
    $tarr["sub_title"] = htmlspecialchars($topic[$i]["sub_title"]);
    $tarr["sub_dt"] = htmlspecialchars($topic[$i]["sub_dt"]);
    $tarr["sub_dt_ago"] = Date2::Ago($topic[$i]["sub_dt"]);
    $tarr["cat_id"] = htmlspecialchars($topic[$i]["cat_id"]);
    $tarr["cat_title"] = htmlspecialchars($topic[$i]["cat_title"]);
    $tarr["cat_dt"] = htmlspecialchars($topic[$i]["cat_dt"]);
    $tarr["cat_dt_ago"] = Date2::Ago($topic[$i]["cat_dt"]); 
    $tarr = base64_encode(json_encode($tarr));
    $search_data[$j]["type"] = "subtopic"; 
    $search_data[$j]["data"] = $tarr;
    $j++;
  }
  print json_encode($search_data);
?>