<style type="text/css">
    #related-diploma-rows {
        float: left;
        width: 700px;
    }
    .related-diploma-row {
        display: flex;
        align-items: center;
        margin-bottom: 8px;
    }
    .related-diploma-input-wrap {
        position: relative;
        display: flex;
        align-items: center;
    }
    .related-diploma-search {
        width: 300px;
    }
    .related-diploma-clear,
    .related-diploma-remove-row {
        border: 0;
        background: transparent;
        color: #c00;
        font-size: 20px;
        line-height: 1;
        cursor: pointer;
        padding: 0 6px;
    }
    .related-diploma-remove-row {
        margin-left: 4px;
    }
    .related-diploma-suggestions {
        position: absolute;
        top: 100%;
        left: 0;
        z-index: 1000;
        width: 300px;
        max-height: 220px;
        overflow-y: auto;
        margin: 0;
        padding: 0;
        list-style: none;
        border: 1px solid #ccc;
        background: #fff;
        display: none;
    }
    .related-diploma-suggestions li {
        padding: 6px 10px;
        cursor: pointer;
        font-family: Tahoma, Arial, sans-serif;
        font-size: 12px;
    }
    .related-diploma-suggestions li:hover {
        background: #f2f2f2;
    }
    #add-related-diploma-row {
        margin-top: 4px;
        padding: 4px 10px;
        cursor: pointer;
        font-family: Tahoma, Arial, sans-serif;
        font-size: 12px;
    }
</style>
