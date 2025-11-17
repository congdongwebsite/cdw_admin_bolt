<?php
global $CDWQRCode;



// require_once(ADMIN_THEME_URL . '/core/function-api-momo.php');
// $apiMomo = new APIMomo(APIMOMOURL);
// $data = $apiMomo->delivery(5000, '', 'https://www.congdongweb.com/admin/?module=client&action=billing&subaction=checkout&id=2656&step=2&payment=momo', '121a', 'Thanh toán đơn hàng ABC', 'https://www.congdongweb.com/admin/?module=client&action=billing&subaction=checkout&id=2656&step=2&payment=momo');
// var_dump($data);
// $out = $CDWQRCode->withLogo($data->data['qrCodeUrl']);
?>
<div class="card profile-header">
    <div class="body  m-0 p-0">
        <div class="row clearfix   m-0 p-0">
            <div class="col-lg-12  pt-5 payment-momo">
                <div class="payment-cta p-0 text-center">
                    <div>
                        <h1>Quét mã QR để thanh toán</h1>
                    </div>
                    <div class="qrcode_scan_container">
                        <div class="qrcode_scan">
                            <div class="qrcode_gradient"><img alt="" src="<?php echo ADMIN_THEME_URL_F; ?>/assets/images/payment/qrcode-gradient.png" class=" img-fluid">
                            </div>
                            <div class="qrcode_border"><img alt="" src="<?php echo ADMIN_THEME_URL_F; ?>/assets/images/payment/border-qrcode.svg" class=" img-fluid">
                            </div>
                            <div class="qrcode_image">
                                <img alt="paymentcode" class="image-qr-code" src="data:image\/png;base64,iVBORw0KGgoAAAANSUhEUgAAASYAAAEmCAIAAABApqqNAAAABnRSTlMA\/wD\/AP83WBt9AAAACXBIWXMAAA7EAAAOxAGVKw4bAAAWp0lEQVR4nO3de3Bc1X0H8LMvrVarpyXLloxsWVi2ZVMC2DwSgh+UR1I6aacZQlpCMrRMM9Ak0Glap20oKW3TpA8ybSlM6FAoE9IWpqSdDkkJbjA2Dg\/HgIuf2MaWJUu2JVmvXUm7q723f9zVouzqrs7Zc36\/e2V\/P39dLueec+6uft7zu+ec3YBt2wIAuAS97gDAxQUhB8AKIQfACiEHwAohB8AKIQfACiEHwAohB8AKIQfACiEHwAohB8AKIQfACiEHwAohB8AKIQfAKqx6QSAQoOgHtu0BKf\/83SqHHIP8q2PbtuqxTJ0ybZnqW6n7nI9Mn031zVRbqvfiVXkPYWAJwAohB8AqoPpBPHtMrPMhXjC29v94ABY0\/\/zdXgi5nNu1ps6byi39lvOo9pn6tfLqNWSGgSUAK4QcACtjuZzMvEeJ8v4cA8AFwz9\/t37P5WTOu5XhzP10+qOTe1DkSBRzdH6o0ycwsARghZADYIVcDi4K\/vm79WMuNxvnekXqOS6da6nXkXqVp8mcd0OxrpUBBpYArBByAKyQy8FFwT9\/t37P5WbjzA10+qnaN4q1izJ9kOkz57yfan9kysvUzwwDSwBWCDkAVtgvBxcF\/\/zd+jGX81ueQL02UqeMTD9Vy+iUN9UfivfaJzCwBGCFkANgpZXLGbSwxgaw4Pjn79bvuRzO4zz2ywFA+RByAKyQy8FFwT9\/t8ohx4BiHsxUW6bWHKr2Tae8qb12nH2jXkfqIQwsAVgh5ABY+XFgCXABMzkvZ2r87dXeMNX7kqlftR5Te9UupL2COu3qvD5EzxQwsARghZADYIVcDoAV1RpL6vkut2t1+qZTp+q1FPvWTM3LUb8XFPfCOa+IXA5gIUHIAbBCLgfAqpxc7kKdC1Kt36s1mdR5iKn3heK1kuknxfMCgzCwBGCFkANghVwOgJVuLud2nnl8bBzFPXqVU6nWqZN36bSrei1nnZiXA1ioEHIArJDLAbDiWGPpdt6rnIF6v9lCqYd6ztCrtZrUa3o1YWAJwAohB8AKuRwAK91cjmLtok67FPMtFPNCpuqhuC+Ztrya1zKV23u4NhgDSwBWCDkAVsjlAFhR7Zejnpej2EOler+q\/fQqd5JpS5Wf8yiK3FWmvCQMLAFYIeQAWCGXA2DF\/T2WpurhzP0o1nbqnJfpJ8W1XvWfcz0kdQ4sMLAEYIaQA2CFXA6AFVUuNxt1zmCqDxS5og7qeTyK3Ik6H+Oc\/ySa88TAEoAVQg6AFXI5AFYmv8fS1LWmxvo6dZpaj0cxp8c5l2iqfs65WZ06ZcqrtlUAA0sAVgg5AFbI5QBYUf0mgdt56rkdmXqo+ybzmrjhXM9JPVdGUUYGxdyjwTlYDCwBWCHkAFghlwNgRfU9ln6Y7+LsM8X8oWr91PmSV\/Njfvi7wrwcwEKFkANghVwOgBXHGkvO\/MSr+k3V6YZiLpRhDmre\/sjci04ZznYlYWAJwAohB8AKuRwAK5PzchT1UK8nNNUf6r1nqm2Zymc413y6XetWnmJukKLPBTCwBGCFkANghVwOgJXJ35eTKePn\/WnUczgU90g9p0SxJpOiLZl6OOdRS8DAEoAVQg6AFXI5AFYcvy9nqh6\/5Xsq9yFbp6kckjrHlkExp6p6Lzp1qt6LJAwsAVgh5ABYIZcDYGVyXo5ivsgPc1l+3henU8bPc24Uc5vUOb8kDCwBWCHkAFghlwNgRbVfztRYXKYMxRyOG7\/lWhR5qalrveqPTruq9aheKzCwBGCGkANghVwOgBX3GkuKORNT+Q91vudnpua1qOfBqOf3dNqShIElACuEHAAr5HJ+l562Dp1JHOhLnBya6BtNjU5mpjKWECIWCdVXhVvqKjuaqta3VK9ZGo+E8A\/oAkAVchRrLy+qORzLtvf3jb98cPCNEyOTGWve8vGK0Ec76m\/ualrfWqPayRL9pFhr6tX8pE55gxByav2XuVamb6W9dWLk2T19xwcmVJsTQqxZEr\/zmtarltcpXYWQY4OQU+u\/zLUyfXNzdiz12Kvde0+NFZyviYY7m6vaGmKN8UgsGhJCTKSyg4l0z\/DU0XPJZDpbUP7a9rp7N69oqq7Q7CdCzjjkcj7y6vtDj+7onj2MrK0Mb13deENnQ2dzPBQMzHlV1rKPnEnuPHZ+x5GhxKzYq46GHvjFldetrCfvN6goJ+QW+twXxZyh5qeiZdvfe\/P0c3vP5M\/UVYZv39DyyfWLoxHZhyKT6ewP9w88\/3Z\/IpULvIAQd1237DMbWua91qvXn+Lvh2Je1+CnIkJOrW8Ub49l29\/deerF\/QP5Mzetbbrn+kuqKz9cqGCnT0+P77Ym99vpXttKChEIhOqC0fZg1RWhmo8FQh9mbqOT00\/sPPXqsfP5M5++cundH7ukdB8QcuXVUwaEnFrfKN6eZ97ozX++RcPBL29t37J6Ue7\/2Znp4f\/MDD6dTb4thMtzy0BFuPbGyOJ7QjUfFyLX9I8PDj6+szuTzXXgrmuX3bGx1GcdQq68esqAXM5jO94f+puXTzjH1dHQQ7d1drVUO\/+ZHXsl1ft1K3VMsqpQ9fXRS74ZjK11\/nNfz9if\/eiYM4kXEOKPPnnpRzsaTHcflJn8lKN+mqTzpEun\/zr9LF1n\/2jqK\/9+wHleEg0H\/\/xTq3PxZmdSvQ9lBv9ZCMV3JxCNLvvTyOK7nf\/a1zP2jRePOp911dHQo59d7\/YM09TTSNd++exTyFR\/ZNoqgPUKXvrHHScnZz6F7r+xPRdv1tTk8c9nBp9UjjchhJ1K9X4t1fugc+1H2mrv3bTC+T+JVPbxV7sNdh7Kg5DzzOsfDL\/bO+4c39LVtKlzkRBC2NOTJ34rO\/4TnZozA0+kT\/9FruZ1TZtX5TLDN0+Ovn1qVKdm0FdOyNkzShy7lVetX7Ue1f7IlNfpp1sZy7affavPOW6IRX7z+jbnONX3l9mx7ZIdKCF97tHp8y84x7+9aXl1NOQc5xs1eC8674vOe2TqvdPpTxk49stRP2Wi7pvqtTJ9eLdn7OTQpHP82atb4tGQECKb2JM597h7v6Phmi3B6muCkVZbZO1UdzbxWjbxpsuTTDvV+4ehmusDkSV1sfDtV7U89XqvEOLI2eSBvvHidZh+yMkpnhh7+GTSDVXIQWkvHRx0DupjkZu7moQQQlip0w8KUbh0SwghRDDS+LmKlq8GIkt+\/vxXrakjqdMPz\/nBaGdH0v3fji5\/RAjxS5ctfm5vv7Mu7OVDg+UtfQYjkMt5YDKd\/Vl3Lqe6aW1jRTgohMiO7bQm3pmjdDBWufKp6PK\/Loo3IYQIVq6JXfq9ipavzdlQ5vxzdvq0ECJWEdq6utE5+foHI5ns\/FsTgAhVyOmMxanH0xR9UOrP\/r7x1HTuj\/6GztyzjczgM3MUDYRjK58K13+iZH2BiqW\/W9H85bluNZM5\/2\/O4aaZhpLp7JEzScn7cisjQ7Ue1feF+piIyZALzChxrFOPalteXetWPu9gf8I5aKiKdDRVCSFsa3J6\/JXil6Jiyf2h2q0yL1pF67ZgZVfx+emRF52DNUvj8YrcQ5QDMx2Yl6nXQbUtU\/VQlNeEgaUHPhjMbYRbsyTuvNFW8h1hFe6OC4RqI833yVYaiFQsfaD4tDV5yJ4+L4QIBQOdzfGCDgA\/hJwH+sdSzkFbQ8w5sKYOFhcL1WwNhKrlqw3X3SqC8aLTljV1eKa5ylwHRqcUugtGmXxiOXsE7HasU49qW15d61Y+bziZcQ6aqiPOgZU+XVwsv1pSVjAWinVlkz8r7EO6b6a53GqvkYlpySpNvQ6qbZmqh6K8JpO\/SRBwmdOgOO9WRqa8qXrcys9ramYTatVMclU8qhRCzPWRNY9AeHHxSdtKFDQ3mZlzKmKuColfc9U6Vfuset74e10MA0svzUrY53ojsiOqFdpW4aNIIYQQkeL2wCsIOQ\/kN3pPzHzcBcKNxcWsycOqNVupORYuB8K56YGJmd3isUiouBjw0B1Ylp3PaJ6X+azXydl02p1XQ1WkfzQlhBhKpJ0zwWhHcbFs4qfCmhLBSvmaQ1UfmU4XRl2w8lLnYCiZa66+SvZ9p37NVeuUYerviijH0\/2Uk5mfoZgnMVWG4tp5La2NOgc9w7knh8H4VcXDPjs7khl+Qanm6PJHossenj1BFwjV5+O55\/xUQQc+LKbxPlK816r9of570ylfAANLDzjT30KII2cTzr+kwWj7rtH7iqMu3f8tZ1ZtXkPJzA\/fG+geDkeav1jV9Ups9f9Emr4QrFxX0fonIhAWQmQt++hAsqADwA\/Lmj3Q1VIt3hFCiKFkpntosr0pJoQYqbrniWPRuzsejQTT+ZJ25uzUiXtilz4rgrESFQ4lM9teOHxmLCWEWN0cv6mraXPn5fG2K2eXef9sMv\/NX+taFab7wCzdTzmKdWsy5U2Vobh2Xpcvq4mEch9ou2a+iusT6xfvPn\/zg+9950RizezC2cTuyaOfnvO5iONQf+L3\/+PQmZnp9ffPJR97tfvzT+979s2f2xq369iwcxCLBNcuKQw5nfeR4r1W7Q\/135tO+QLlhJzO2JfiWKdd6nucU1VFaMPMF5hvPzw4nbWFEA3xyKcuX3Iy2f7H7\/3tY0e3nZpYlS+fndg7cXhzqvfr1sR7ws59UtlW+v3+wb966fi2Hxw5N54uaCI1bT3\/dn9+x0AqY71yZMg5vm5lvbN3Qed+Zei8\/jr1UP8tacLA0hu3dDW9cWJECDGUzPzv4cFb1y8WQtyxsWX38eG+0amdA5t2DdzQUX1iQ8Nba2oPLoudqg6PZs89PXH2mYTVdjqz8fBY156zK7oTc0x8513ZVpv\/LZ4fHRgYT+VWnNy8ron45qAUhJw3NrbXtTVUOk8sv7+nb1PnolhFqDIS\/INbOrb94HBq2rJF4Hii43gi97AxErBCgey0HZq2pQYmi6sr7r+x3Tken5p+fm+\/c7xqcdVl2J\/qKd3vPlEtT3Gs0y71PboJBgK\/cXWrczyUzPzLG7k1lquaq7bd0hEu+vmBjB2csiKS8VYXC3\/jlzvrq3KLTp58rWd0KvcRd+c1rcG5Rkea+cmcdF5\/nXqo\/5Y0Uc3LUdRJMeZWrdPg\/V6\/qmH9zJPDF98798YHueVd16ysf+i2zvzeNlVLaqLf+tU1KxpzTzh3HBnaPpPFbVhee3W72q+C6ORObmVkznOWMfieysC8nGeCgcCXtqyIhoNCCFuIR7afOHYuN2925fLa79y+rmtpOY\/yW+qibYty8XawP\/EPO3KPOmOR4H2bV5joOGhByHmprSH2xRtyX6c3kck+9N9H81HXWh\/99q+teeDG9pa6wpUipe3rHTsxOCGEONSfePjFo843PgSE+MrW9iVFi06AH36TwHv\/9Nqp\/9p3zjmORUK\/d9PK6zo+HP5lLfvdnrHXjg3v6x0bSKZnv13BQGBZffTKttrNnY2Tmeyuo+f\/7\/T42fHUr1y+pLM5\/nevnMx\/w8odG1vuunYZ4z2BK5Mhlx8B2zS\/VSDTFkX91O1atv33Pzm5\/XAu4woIcdsvNH\/humWxonRubHJ6IJGeSGUDAVEdDTfXVlQVlTk7lnrqp72vHR\/On7ntssX3zjekVL0XmXvUqVOnfpk6da51Ky8JITd\/\/QztWrb95O6e\/GedEKIxHvn1q1tvWtsUnlmnMq\/0tPXyocF\/3dM\/MpnJn\/zMhqWfu3bZnE8pZfqMkJMvLwkhN3\/9bO3++ODAd3f15EeDQohF8cjNa5s+vqqhvbHKLWpsW3wwOLHr6Pnth4dmB1ssEvzSlhWbV8+xE68YQk7+WrfykpDL+UvP8OSjO7oP9BV+6V1jPLK6Od62KNYUjzgDzol0diiROTU8eeRscngiU1D+iktqfmeL8qMXYKD7+3IU1xr8F8V4uwyf5JZt7z42\/P09ffnddEraG2N3XtOq8+uNnKMSU5+iqn0zda1M+cLLEXJllyEdZFq2vbd79KWDg3tPjeZ\/f7iEaDi4cUXdreuarmirnTdzKw0hJ3+tTPnCyxFyZZehzicdE+ns\/tPjB\/oTJ4cm+kZTIxMZ5wvCKiPBhnikpTba0VS1rqX6staa4iec5UHIyV8rU77wcuRyAJzK2Ungh3\/tqJ9WqdapWs9CRz1q0KlTFXO7WPAFwAohB8AKuRwAK5O5HEXuZIqpPnDmh9RPUCleE516ZOo3dSzTlk6ZEjCwBGCFkANghVwOgJVuLqeT11HPsVDMAVKsctDJH6jzHM7XkKKMkMCZ9woMLAGYIeQAWCGXA2BF9W3NnPkP9apwU2sFqeffqNepUpTxKr\/VKa8JA0sAVgg5AFbI5QBYca+x5FxPqNpnU9eayrV0UKz\/VD1P8Tr4Yf5QEwaWAKwQcgCskMsBsDK5xtIPaxFl6qfoj9\/WbaqizqtN5W9+W0NbBgwsAVgh5ABYIZcDYGVyjaUf5mpMjdc594bJ9IGzjKmcmfrvgToHxrwcwIUAIQfACrkcACvdXM7UPAz12F2njOq1XuVFnH2gyI11+uNWRue8TB\/ke56HgSUAK4QcACvkcgCsTOZy1OvuKNYT6tSjU7+f99RRzH1RtCXTB4rymjCwBGCFkANghVwOgBVVLmeqHlNzQap9MNU3zrlBzhxMp586\/dFpl3qtryQMLAFYIeQAWCGXA2BFtcZS51rqPWYy15ra96Va3lRbqkztVfPDnjeKPYQyfZOEgSUAK4QcACvkcgCsOH5fTue8Tls65U3lWqr9oW6XOm\/0ao2oTBmKedcyuo2BJQArhBwAK+RyAKxM\/r6cWxnqfXQ6\/VTtv2pbpuavdK6lfs1N9d+rOTfO\/FNgYAnADCEHwAq5HAArjt8K1+geyfedcO6jM1WPV+tOdfppao6LYk6y1H3OB\/NyAAsJQg6AFXI5AFZ+3C+nU94Pe+qo54Uo5vE4+8a5X86H60UxsARghZADYIVcDoAV928SeDXWp8gZTNVjKr8y1TfOPIfiWQB1H5DLASwkCDkAVsjlAFiZ\/O4TnZxNpk5T7c5\/J\/Tfq6HaB4o8WaZd1b6Zapdz3SnRvjg3GFgCsELIAbBCLgfAiuO7T3Tqp1jnRr1Oz9R5mf6r9lm1TtX+UK971GlXtU7VtiRhYAnACiEHwAq5HAArjt8Kp95bxZmbUcwFUR+r9setvA6K11C1Lc55vxIwsARghZADYIVcDoAV1e\/L6aCYI6KYK6PYV8Y5T0WdK+r0meK1Ve2DahlJGFgCsELIAbBSzuXyn6pmIaWEi4Qfczk3FPmJTrsy5Snm+ijmOb2a81TlVf5scN4SA0sAVgg5AFZauZzOh2xBTohcDi4SCzWX45w3o8hPOPd6qZbnzIUo1j1S594ydZaAgSUAK4QcACtjuZzMfF2J8sjl4CLhx1zOq7V2ppjaq8a574t675zf1m0y52+zYWAJwAohB8AKuRwAK7\/ncjJlTO258nOOQXEvfliraeq+ZMqr3qPM+TJgYAnACiEHwAq5HAArv+dyfliD51aPzHmd+1K9VqetCynPdOuDTN90ykjCwBKAFUIOgBX2ywGw8mMu54YzlzO154pivSj13JRM30zNYXLmk27t6pwvAwaWAKwQcgCs8D2WAKwWai4nc96tDPW8H3W7nHmaTHnVOilyMNU+6NSpCQNLAFYIOQBWyOUAWOEnHQFYYWAJwAohB8AKIQfACiEHwAohB8AKIQfACiEHwAohB8AKIQfACiEHwAohB8AKIQfACiEHwAohB8AKIQfACiEHwOr\/AUFx11oCryy4AAAAAElFTkSuQmCC">
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="jsx-d22f6bd0771ae323 mr-1 inline h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" class="jsx-d22f6bd0771ae323"></path>
                        </svg>

                        <a>Sử dụng <b> App MoMo </b> hoặc ứng dụng camera hỗ trợ QR code để quét mã</a>
                    </div>

                    <div class="text-center text-nowrap mt-3 font-size-14">
                        <div class="mr-1 d-inline">Gặp khó khăn khi thanh toán? </div>
                        <a class="yellow-color font-weight-bold-600 hover-none-decoration" href="#" onclick="return showModal()">Xem Hướng dẫn</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="expire-content mb-3">
                    <div class="box-expire">
                        <div class="expire-text">
                            <div>
                                Đơn hàng sẽ hết hạn sau:
                                <br>
                                <div class="font-weight-bold time-expire-text d-inline-flex">
                                    <div class="time-box">00<br>
                                        <p>Giờ</p>
                                    </div>
                                    <div class="time-box">00<br>
                                        <p>Phút</p>
                                    </div>
                                    <div class="time-box">00<br>
                                        <p>Giây</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>