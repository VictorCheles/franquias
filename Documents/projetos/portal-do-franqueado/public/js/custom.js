/**
 * Tradução fullcalendar
 */
(function(e){"function"==typeof define&&define.amd?define(["jquery","moment"],e):e(jQuery,moment)})(function(e,t){(t.defineLocale||t.lang).call(t,"pt-br",{months:"Janeiro_Fevereiro_Março_Abril_Maio_Junho_Julho_Agosto_Setembro_Outubro_Novembro_Dezembro".split("_"),monthsShort:"jan_fev_mar_abr_mai_jun_jul_ago_set_out_nov_dez".split("_"),weekdays:"domingo_segunda-feira_terça-feira_quarta-feira_quinta-feira_sexta-feira_sábado".split("_"),weekdaysShort:"dom_seg_ter_qua_qui_sex_sáb".split("_"),weekdaysMin:"dom_2ª_3ª_4ª_5ª_6ª_sáb".split("_"),longDateFormat:{LT:"HH:mm",LTS:"LT:ss",L:"DD/MM/YYYY",LL:"D [de] MMMM [de] YYYY",LLL:"D [de] MMMM [de] YYYY [às] LT",LLLL:"dddd, D [de] MMMM [de] YYYY [às] LT"},calendar:{sameDay:"[Hoje às] LT",nextDay:"[Amanhã às] LT",nextWeek:"dddd [às] LT",lastDay:"[Ontem às] LT",lastWeek:function(){return 0===this.day()||6===this.day()?"[Último] dddd [às] LT":"[Última] dddd [às] LT"},sameElse:"L"},relativeTime:{future:"em %s",past:"%s atrás",s:"segundos",m:"um minuto",mm:"%d minutos",h:"uma hora",hh:"%d horas",d:"um dia",dd:"%d dias",M:"um mês",MM:"%d meses",y:"um ano",yy:"%d anos"},ordinalParse:/\d{1,2}Âº/,ordinal:"%dÂº"}),e.fullCalendar.datepickerLang("pt-br","pt-BR",{closeText:"Fechar",prevText:"&#x3C;Anterior",nextText:"PrÃ³ximo&#x3E;",currentText:"Hoje",monthNames:["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"],monthNamesShort:["Jan","Fev","Mar","Abr","Mai","Jun","Jul","Ago","Set","Out","Nov","Dez"],dayNames:["Domingo","Segunda-feira","Terça-feira","Quarta-feira","Quinta-feira","Sexta-feira","Sábado"],dayNamesShort:["Dom","Seg","Ter","Qua","Qui","Sex","Sáb"],dayNamesMin:["Dom","Seg","Ter","Qua","Qui","Sex","Sáb"],weekHeader:"Sm",dateFormat:"dd/mm/yy",firstDay:0,isRTL:!1,showMonthAfterYear:!1,yearSuffix:""}),e.fullCalendar.lang("pt-br",{defaultButtonText:{month:"Mês",week:"Semana",day:"Dia",list:"Compromissos"},allDayText:"dia inteiro",eventLimitText:function(e){return"mais +"+e}})});

(function (factory) {
    if (typeof define === 'function' && define.amd) {
        define(['moment'], factory); // AMD
    } else if (typeof exports === 'object') {
        module.exports = factory(require('../moment')); // Node
    } else {
        factory(window.moment); // Browser global
    }
}(function (moment) {
    return moment.defineLocale('pt-br', {
        months : 'janeiro_fevereiro_março_abril_maio_junho_julho_agosto_setembro_outubro_novembro_dezembro'.split('_'),
        monthsShort : 'jan_fev_mar_abr_mai_jun_jul_ago_set_out_nov_dez'.split('_'),
        weekdays : 'domingo_segunda-feira_terça-feira_quarta-feira_quinta-feira_sexta-feira_sábado'.split('_'),
        weekdaysShort : 'dom_seg_ter_qua_qui_sex_sáb'.split('_'),
        weekdaysMin : 'dom_seg_ter_qua_qui_sex_sáb'.split('_'),
        longDateFormat : {
            LT : 'HH:mm',
            L : 'DD/MM/YYYY',
            LL : 'D [de] MMMM [de] YYYY',
            LLL : 'D [de] MMMM [de] YYYY [às] LT',
            LLLL : 'dddd, D [de] MMMM [de] YYYY [às] LT'
        },
        calendar : {
            sameDay: '[Hoje às] LT',
            nextDay: '[Amanhã às] LT',
            nextWeek: 'dddd [às] LT',
            lastDay: '[Ontem às] LT',
            lastWeek: function () {
                return (this.day() === 0 || this.day() === 6) ?
                    '[Último] dddd [às] LT' : // Saturday + Sunday
                    '[Última] dddd [às] LT'; // Monday - Friday
            },
            sameElse: 'L'
        },
        relativeTime : {
            future : 'em %s',
            past : '%s atrás',
            s : 'segundos',
            m : 'um minuto',
            mm : '%d minutos',
            h : 'uma hora',
            hh : '%d horas',
            d : 'um dia',
            dd : '%d dias',
            M : 'um mês',
            MM : '%d meses',
            y : 'um ano',
            yy : '%d anos'
        },
        ordinal : '%dº'
    });
}));

function startMaxlength()
{
    $('[maxlength]').each(function(i, elem){
        var threshold = parseInt(parseInt($(elem).attr('maxlength')) / 2);
        var config = {
            threshold: threshold,
            warningClass: "label label-success",
            limitReachedClass: "label label-danger"
        };
        if($(elem).data('placement'))
        {
            config['placement'] = $(elem).data('placement');
        }
        var input = $(elem).maxlength(config);
    });
}


$(function(){
    if($('.date-range').length > 0)
    {
        $('.date-range').each(function(i, data){
            $(data).daterangepicker({
                locale: {
                    applyLabel: 'Selecionar',
                    cancelLabel: 'Cancelar',
                    format: 'DD/MM/YYYY',
                    language: 'pt-BR'
                }
            });

            $(data).on('cancel.daterangepicker', function(ev, picker) {
                $(data).val('');
            });
        });
    }

    if($('[data-mask]').length > 0)
    {
        $('[data-mask]').each(function(i, data){
            $(data).mask($(data).data('mask'));
        });
    }

    if($('.datepicker').length > 0)
    {
        $('.datepicker').each(function(i, picker){
            var options = {
                language: 'pt-BR',
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true
            };
            if($(picker).data('enddate'))
            {
                options['endDate'] = $(picker).data('enddate');
            }
            if($(picker).data('startdate'))
            {
                options['startDate'] = $(picker).data('startdate');
            }
            if($(picker).data('multidate'))
            {
                options['multidate'] = true;
            }
            $(picker).datepicker(options);
        });

    }

    if($('.default-datetimepicker').length > 0)
    {
        $('.default-datetimepicker').each(function(i, item){
            $(item).datetimepicker({
                format: 'Y-m-d H:i'
            });
        });
    }

    if($('.wysihtml5').length > 0)
    {
        $('.wysihtml5').wysihtml5();
    }

    if($('[rel=tooltip]').length > 0)
    {
        $('[rel=tooltip]').tooltip();
    }

    if($('[maxlength]').length > 0)
    {
        startMaxlength();
    }
});