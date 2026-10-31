class PrayerTimeModel {
  bool? status;
  String? message;
  Data? data;

  PrayerTimeModel({this.status, this.message, this.data});

  PrayerTimeModel.fromJson(dynamic json) {
    if (json is! Map) return;
    status = json['status'] == true || json['status'] == 1 || json['status'] == 'true';
    message = json['message']?.toString();
    data = json['data'] != null ? Data.fromJson(json['data']) : null;
  }

  Map<String, dynamic> toJson() {
    final Map<String, dynamic> data = <String, dynamic>{};
    data['status'] = status;
    data['message'] = message;
    if (this.data != null) {
      data['data'] = this.data!.toJson();
    }
    return data;
  }
}

class Data {
  String? date;
  bool? isJumma;
  String? imsak;
  String? fajrStart;
  String? sunrise;
  String? zuhrStart;
  String? asrStart;
  String? maghribStart;
  String? ishaStart;
  String? sehriEnd;
  String? iftarStart;

  Data({
    this.date,
    this.isJumma,
    this.imsak,
    this.fajrStart,
    this.sunrise,
    this.zuhrStart,
    this.asrStart,
    this.maghribStart,
    this.ishaStart,
    this.sehriEnd,
    this.iftarStart,
  });

  Data.fromJson(dynamic json) {
    if (json is! Map) return;
    date = json['date']?.toString();
    isJumma = json['is_jumma'] == true || json['is_jumma'] == 1;
    imsak = json['imsak']?.toString();
    fajrStart = json['fajr_start']?.toString();
    sunrise = json['sunrise']?.toString();
    zuhrStart = json['zuhr_start']?.toString();
    asrStart = json['asr_start']?.toString();
    maghribStart = json['maghrib_start']?.toString();
    ishaStart = json['isha_start']?.toString();
    sehriEnd = json['sehri']?.toString();
    iftarStart = json['iftar']?.toString();
  }

  Map<String, dynamic> toJson() {
    final Map<String, dynamic> data = <String, dynamic>{};
    data['date'] = date;
    data['is_jumma'] = isJumma;
    data['imsak'] = imsak;
    data['fajr_start'] = fajrStart;
    data['sunrise'] = sunrise;
    data['zuhr_start'] = zuhrStart;
    data['asr_start'] = asrStart;
    data['maghrib_start'] = maghribStart;
    data['isha_start'] = ishaStart;
    data['sehri'] = sehriEnd;
    data['iftar'] = iftarStart;
    return data;
  }
}
