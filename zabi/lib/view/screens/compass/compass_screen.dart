import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:flutter_qiblah/flutter_qiblah.dart';
import 'package:get/get.dart';
import 'package:zabi/shimmer/all_shimmer_loder.dart';
import 'package:zabi/view/base/custom_app_bar.dart';
import 'package:zabi/view/screens/compass/widget/qiblah_compass.dart';

class CompassScreen extends StatefulWidget {
  final bool appBackButton;
  const CompassScreen({super.key, required this.appBackButton});

  @override
  State<CompassScreen> createState() => _CompassScreenState();
}

class _CompassScreenState extends State<CompassScreen> {
  final Future<bool?>? _deviceSupport =
      kIsWeb ? null : FlutterQiblah.androidDeviceSensorSupport();

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      // Appbar start ===>
      appBar: CustomAppBar(
        title: 'qibla_compass'.tr,
        isBackButtonExist: widget.appBackButton == true ? true : false,
      ),

      body: kIsWeb
          ? Center(
              child: Padding(
                padding: const EdgeInsets.all(24.0),
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Icon(Icons.explore_outlined,
                        size: 72, color: Theme.of(context).primaryColor),
                    const SizedBox(height: 16),
                    Text(
                      'Qibla Compass requires magnetic hardware sensors, available on mobile devices (Android / iOS).'
                          .tr,
                      textAlign: TextAlign.center,
                      style: const TextStyle(
                          fontSize: 16, fontWeight: FontWeight.w500),
                    ),
                  ],
                ),
              ),
            )
          : FutureBuilder(
              future: _deviceSupport,
              builder: (_, AsyncSnapshot<bool?> snapshot) {
                // Loading section---->
                if (snapshot.connectionState == ConnectionState.waiting) {
                  return const QuiblaeShimmerScreen();
                }
                // Error message show here--.
                if (snapshot.hasError) {
                  return Center(
                    child: Text("error: ${snapshot.error.toString()}".tr),
                  );
                }

                if (snapshot.data == true) {
                  // QiblahCompass page return here-->
                  return const QiblahCompassWidget();
                }
                // error message---.
                return Center(
                    child: Text('our_compass_not_support_in_your_device'.tr));
              },
            ),
    );
  }
}
