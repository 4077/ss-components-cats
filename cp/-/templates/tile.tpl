<div class="{__NODE_ID__}" instance="{__INSTANCE__}">

    <div class="appearance">
        <div class="row">
            <div class="label">Шаблон плитки</div>
            <div class="control">
                {TEMPLATE_SELECTOR}
            </div>
        </div>

        <div class="row">
            <div class="label">Размер картинки</div>
            <div class="control image_size">
                <input type="text" class="image_dimension" field="width" value="{IMAGE_WIDTH}">
                <input type="text" class="image_dimension" field="height" value="{IMAGE_HEIGHT}">
            </div>
        </div>

        <div class="row">
            <div class="label">Открывать в новой вкладке</div>
            <div class="control">
                {OPEN_IN_NEW_TAB_VIEW_TOGGLE}
            </div>
        </div>
    </div>

    <div class="options">
        {*<div class="row">
            <div class="label">Кнопка корзины</div>
            <div class="control">
                {CARTBUTTON_TOGGLE}
            </div>
        </div> *}
    </div>

</div>
