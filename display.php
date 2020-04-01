<?php
require_once "config.php";
if(isset($_REQUEST['delid'])){
    $delid=$_REQUEST['delid'];
    $query="delete from users where Id='$delid'";
    $output = mysqli_query($conn,$query);
}
$sql = "SELECT * from users";
$data = mysqli_query($conn,$sql);
$total = mysqli_num_rows($data);

?>

<table border="1">
<tr>
<th>Id</th>
<th>username</th>
<th>password</th>
<th>email</th>
<th>gender</th>
<th>city</th>
<th>hobbies</th>
</tr>
<?php
for($i=1;$i<=$total;$i++)
{
    $result = mysqli_fetch_assoc($data);
?>
<tr>
<td><?php echo $result['Id']?></td>
<td><?php echo $result['username']?></td>
<td><?php echo $result['password']?></td>
<td><?php echo $result['email']?></td>
<td><?php echo $result['gender']?></td>
<td><?php echo $result['city']?></td>
<td><?php echo $result['hobbies']?></td>
<td><a href="display.php?delid=<?php echo $result['Id'] ?>">Delete</a></td>

</tr>
<?php
}
?>
</table>
