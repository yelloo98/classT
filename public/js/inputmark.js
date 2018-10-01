/** :: EnjoyWorks ::
 ***********************************************************************************************************************
 * @source  :
 * @project : Pro_DivingMall
 *----------------------------------------------------------------------------------------------------------------------
 * VER  DATE           AUTHOR          DESCRIPTION
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * 1.0  2018/01/05     Name_0070
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * Project Description
 * Copyright(c) 2015 enjoyworks Co., Ltd. All rights reserved.
 **********************************************************************************************************************/

"use strict";
//######################################################################################################################
//##
//## >> Option : Input Mark
//##
//######################################################################################################################
var markOptions = {
    phone : {
        onKeyPress: function(cep, e, field, options) {
            var name = $(field).attr('name');
            if(/^1.*$/.test(cep)) {
                $('input[name=' + name + ']').mask('X000-0000', options);
            }else if(/^\(?01.*$/.test(cep) && cep.length <= 12){
                $('input[name=' + name + ']').mask('X00-000-00000', options);
            }else if(/^\(?01.*$/.test(cep) && cep.length > 12){
                $('input[name=' + name + ']').mask('X00-0000-0000', options);
            }else if(/^\(?02.*$/.test(cep) && cep.length <= 13){
                $('input[name=' + name + ']').mask('(X0)-000-00000', options);
            }else if(/^\(?02.*$/.test(cep) && cep.length > 13){
                $('input[name=' + name + ']').mask('(X0)-0000-0000', options);
            }else if(/^\(?0[3-9].*$/.test(cep) && cep.length <= 14){
                $('input[name=' + name + ']').mask('(X00)-000-00000', options);
            }else if(/^\(?0[3-9].*$/.test(cep) && cep.length > 14){
                $('input[name=' + name + ']').mask('(X00)-0000-0000', options);
            }
        },
        translation: {
          'X': {
              pattern: /[0|1]/
            }
        }
    },
    password: {
        translation: {
            'X': {
                pattern: /[a-zA-Z0-9!@#$]/
            }
        }
    },
    en: {
        translation: {
            'X': {
                pattern: /[a-z|A-Z| ]/
            }
        }
    },
    hyphenNum: {
        translation: {
            'X': {
                pattern: /[0-9|\-]/
            }
        }
    },
    float: {
        translation: {
            'X': {
                pattern: /[0-9|.]/
            }
        }
    },
}