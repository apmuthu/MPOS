<html>
<head>
    <title>Test</title>
    <script type="text/javascript">
        <!--
        function clone_this(objButton) {
            /**
             *  The div in which the first input is located is cloned
             **/

            tmpNode = objButton.form.elements[0].parentNode.cloneNode(true);


            /**
             *  the cloned div is inserted before the button
             *  Syntax...parentNode.insertBefore(nodeToBeInserted,nodeBeforeInsertion);
             **/

            objButton.form.insertBefore(tmpNode, objButton);


            /**  Delete the value of the inserted input
             * previousSibling is the previous node of one type before another node ...
             * in this case the new div before the button .... firstChild again the first child element in it ... so the input
             **/

            objButton.previousSibling.firstChild.value = '';

        }

        //-->
    </script>
</head>
<body>
<form action="empfang.php" method="post">
    <div><input size="20" name="textfeldname[]" type="text"></div>
    <input value="noch eins" onclick="clone_this(this)" type="button">
    <input type="submit" name="submit">
</form>
</body>
</html>
