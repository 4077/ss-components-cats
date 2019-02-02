<div class="{__NODE_ID__}" instance="{__INSTANCE__}">

    <div class="grid">
        <div class="row">
            <div class="label">Показывать название</div>
            <div class="control">
                {DISPLAY_NAME_TOGGLE}
            </div>
        </div>

        <div class="row">
            <div class="label">Показывать описание</div>
            <div class="control">
                {DISPLAY_DESCRIPTION_TOGGLE}
            </div>
        </div>

        <div class="row">
            <div class="label">Лимит</div>
            <div class="control">
                {LIMIT_TOGGLE}
            </div>
        </div>

        <!-- if limit_enabled -->
        <div class="row l2">
            <div class="label">Сначала показывать</div>
            <div class="control">
                <input type="text" class="limit_value" field="limit_value" value="{LIMIT_VALUE}">
            </div>
        </div>

        <div class="row l2">
            <div class="label">Догружать по</div>
            <div class="control">
                <input type="text" class="limit_load_value" field="limit_load_value" value="{LIMIT_LOAD_VALUE}">
            </div>
        </div>
        <!-- / -->
    </div>

    <div class="tile">
        {TILE}
    </div>

</div>
