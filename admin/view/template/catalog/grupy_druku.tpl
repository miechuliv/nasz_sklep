<?php echo $header; ?>

<div id="content">
    <?php if ($success) { ?>
    <div class="success"><?php echo $success; ?></div>
    <?php } ?>
    <div class="buttons" >
        <div class="button" onclick="$('.content form').submit();" ><?php echo $this->language->get('text_submit'); ?></div>
    </div>
</div>
<div class="content">
    <form method="post"  >
        <table id="tb">
            <thead>
            <tr style="border-bottom: 1px solid black;">
                <td><?php echo $this->language->get('text_grupa_druku'); ?></td>
                <td></td>
            </tr>
            </thead>
            <tbody>
            <?php $grupy_row = 0; ?>
            <?php foreach($grupy_druku as $key => $gr) { ?>
            <tr style="border-bottom: 1px solid black;">
                <td style="width:80px;text-align: center;font-size: 20px;font-weight: bold;"><?php echo $key; ?></td>

                <td>
                    <table class="subtb">
                        <thead>
                        <tr>
                            <td>Od</td>
                            <td>Do</td>
                            <td>Cena</td>
                            <td>Ile kolorów</td>
                            <td></td>
                        </tr>

                        </thead>
                        <tbody>
                        <?php foreach($gr as $grupa){ ?>
                        <tr>
                            <td>
                                <input type="hidden" name="grupy_druku[<?php echo $grupy_row; ?>][grupa_druku]" value="<?php echo $grupa['grupa_druku']; ?>" />
                                <input type="text" name="grupy_druku[<?php echo $grupy_row; ?>][from]" value="<?php echo $grupa['from']; ?>" /></td>
                            <td><input type="text" name="grupy_druku[<?php echo $grupy_row; ?>][to]" value="<?php echo $grupa['to']; ?>" /></td>
                            <td><input type="text" name="grupy_druku[<?php echo $grupy_row; ?>][price]" value="<?php echo $grupa['price']; ?>" /></td>
                            <td><input type="text" name="grupy_druku[<?php echo $grupy_row; ?>][colors]" value="<?php echo $grupa['colors']; ?>" /></td>
                            <td><div class="button" onclick="$(this).parent().parent().remove()"><img src="view/image/delete.png" /><?php echo $this->language->get('text_remove_grupa_druku'); ?></div></td>
                        </tr>
                        <?php $grupy_row++; ?>
                        <?php } ?>
                        </tbody>
                        <tfoot>
                        <td><div onclick="addPodGrupaDruku(this)" class="button">
                                <img src="view/image/add.png" /><?php echo $this->language->get('text_add_podgrupa_druku'); ?>
                            </div></td>
                        </tfoot>
                    </table>
                </td>

            </tr>

            <?php } ?>
            </tbody>

            <tfoot>
            <tr>
                <td><div class="button">
                        <input type="text" name="nazwa" />
                        <img onclick="addGrupaDruku(this)" src="view/image/add.png" /><?php echo $this->language->get('text_add_grupa_druku'); ?>
                    </div></td>
            </tr>
            </tfoot>
        </table>

    </form>
    <script type="text/javascript">
        var grupa_druku_row = '<?php echo $grupy_row; ?>';
        function addPodGrupaDruku(elem)
        {

            var textu = $(elem).parents('.subtb').parent().prev().text().trim();

            var html = '';
            html += '<tr>';
            html += '<td><input type="hidden" name="grupy_druku['+grupa_druku_row+'][grupa_druku]" value="'+textu+'"  /><input type="text" name="grupy_druku['+grupa_druku_row+'][from]"  /></td>';
            html += '<td><input type="text" name="grupy_druku['+grupa_druku_row+'][to]"  /></td>';
            html += '<td><input type="text" name="grupy_druku['+grupa_druku_row+'][price]" /></td>';
            html += '<td><input type="text" name="grupy_druku['+grupa_druku_row+'][colors]" /></td>';
            html += '<td><div class="button" onclick="$(this).parent().parent().remove()"><img src="view/image/delete.png" /><?php echo $this->language->get('text_remove_grupa_druku'); ?></div></td>';
            html += '</tr>';

            $(elem).parents('.subtb').find('tbody').append(html);


            grupa_druku_row++;
        }

        function addGrupaDruku(elem)
        {
            var textu = $(elem).parent().find('input').val();
            var html = '';

            html += '<tr style="border-bottom: 1px solid black;">';




            html += '<td style="width:80px;text-align: center;font-size: 20px;font-weight: bold;">'+textu+'</td>';

            html += '<td>';
            html += '<table class="subtb">';
            html += '<thead>';
            html += '<tr>';
            html += '<td>Od</td>';
            html += '<td>Do</td>';
            html += '<td>Cena</td>';
            html += '<td>Ile kolorów</td>';
            html += '<td></td>';
            html += '</tr>';

            html += '</thead>';
            html += '<tbody>';

            html += '<tr>';
            html += '<td><input type="hidden" name="grupy_druku['+grupa_druku_row+'][grupa_druku]" value="'+textu+'"  /><input type="text" name="grupy_druku['+grupa_druku_row+'][from]"  /></td>';
            html += '<td><input type="text" name="grupy_druku['+grupa_druku_row+'][to]"  /></td>';
            html += '<td><input type="text" name="grupy_druku['+grupa_druku_row+'][price]" /></td>';
            html += '<td><input type="text" name="grupy_druku['+grupa_druku_row+'][colors]" /></td>';
            html += '<td><div class="button" onclick="$(this).parent().parent().remove()"><img src="view/image/delete.png" /><?php echo $this->language->get('text_remove_grupa_druku'); ?></div></td>';
            html += '</tr>';

            html += '</tbody>';
            html += '<tfoot>';
            html += '<td><div onclick="addPodGrupaDruku(this)">';
            html += '<img class="button" src="view/image/add.png" /><?php echo $this->language->get('text_add_podgrupa_druku'); ?>';
            html += '</div></td>';
            html += '</tfoot>';
            html += '</table>';
            html += '</td>';

            html += '</tr>';

            $('#tb > tbody').append(html);

            grupa_druku_row++;

        }
    </script>

</div>
<?php echo $footer; ?>