// ignore_for_file: deprecated_member_use

import 'package:flutter/material.dart';
import 'package:flutter_svg/flutter_svg.dart';
import 'package:get/get.dart';
import 'package:zabi/controller/nearby_mosque_controller.dart';
import 'package:zabi/helper/translator_helper.dart';
import 'package:zabi/shimmer/all_shimmer_loder.dart';
import 'package:zabi/view/base/custom_app_bar.dart';
import 'package:url_launcher/url_launcher.dart';
import 'package:zabi/view/base/custom_snackbar.dart';
import 'package:zabi/view/base/location_error_widget.dart';
import '../../../util/dimensions.dart';
import '../../../util/images.dart';
import '../../../util/styles.dart';

class NearbyMosque extends StatelessWidget {
  final bool appBackButton;
  const NearbyMosque({super.key, required this.appBackButton});

  @override
  Widget build(BuildContext context) {
    Get.put(NearbyMosqueController()).getLocation();
    return Scaffold(
      // Appbar start ===>
      appBar: CustomAppBar(
        title: 'nearby_mosque'.tr,
        isBackButtonExist: appBackButton == true ? true : false,
      ),

      // body start ==>
      body: SingleChildScrollView(
        child: GetBuilder<NearbyMosqueController>(
          init: NearbyMosqueController(),
          builder: (nearbyMosqueController) {
            return Obx(
              () => nearbyMosqueController.isLoading.value ||
                      nearbyMosqueController.userLocation.value == ""
                  ? SizedBox(
                      height: Get.height / 1.2,
                      child: Center(
                        child: nearbyMosqueController.isLocationDenied.value ==
                                true
                            ? LocationErrorWidget(
                                error:
                                    "location_service_permission_denied_for_getting_this_service_please_enable_location"
                                        .tr,
                              )
                            : const NearbyMosqueShimmerScreen(),
                      ),
                    )
                  : Padding(
                      padding: const EdgeInsets.symmetric(
                        horizontal: Dimensions.PADDING_SIZE_SMALL,
                        vertical: Dimensions.PADDING_SIZE_SMALL,
                      ),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.stretch,
                        children: [
                          Text(
                            "if_you_cant_find_the_mosque_according_to_the_predefined_area_then_search_for_the_mosque_by_selecting_area_by_kilometers_from_below"
                                .tr,
                            textAlign: TextAlign.justify,
                            style: robotoMedium.copyWith(
                              fontSize: Dimensions.FONT_SIZE_DEFAULT,
                              color: Theme.of(context).primaryColor,
                            ),
                          ),
                          const SizedBox(height: 10),

                          // Distance dropdown ==>
                          Obx(
                            () => Container(
                              padding: const EdgeInsets.symmetric(
                                horizontal: Dimensions.PADDING_SIZE_SMALL,
                              ),
                              decoration: BoxDecoration(
                                borderRadius: BorderRadius.circular(
                                  Dimensions.RADIUS_SMALL,
                                ),
                                border: Border.all(
                                  color: Theme.of(context).primaryColor,
                                  width: 1,
                                ),
                              ),
                              child: Padding(
                                padding: const EdgeInsets.symmetric(
                                  vertical: Dimensions.PADDING_SIZE_EXTRA_SMALL,
                                ),
                                child: DropdownButton<String>(
                                  menuMaxHeight: 500,
                                  value: nearbyMosqueController.km.value,
                                  items: <String>[
                                    '1  KM',
                                    '2  KM',
                                    '3  KM',
                                    '4  KM',
                                    '5  KM',
                                  ].map((String value) {
                                    return DropdownMenuItem<String>(
                                      value: value,
                                      child: Text(translateText(value)),
                                    );
                                  }).toList(),
                                  onChanged: (String? newValue) {
                                    nearbyMosqueController.loadKmDropdownValue(
                                      newValue!,
                                    );
                                    nearbyMosqueController.searchNearbyPlaces();
                                  },
                                  isExpanded: true,
                                  underline: const SizedBox(),
                                ),
                              ),
                            ),
                          ),
                          const SizedBox(height: 12),

                          // Mosques List or Empty State ===>
                          if (nearbyMosqueController.places.isEmpty)
                            Padding(
                              padding: const EdgeInsets.symmetric(
                                vertical: 48.0,
                                horizontal: 16.0,
                              ),
                              child: Column(
                                mainAxisAlignment: MainAxisAlignment.center,
                                children: [
                                  Container(
                                    width: 80,
                                    height: 80,
                                    decoration: BoxDecoration(
                                      shape: BoxShape.circle,
                                      color: Theme.of(context)
                                          .primaryColor
                                          .withOpacity(0.12),
                                    ),
                                    child: Icon(
                                      Icons.mosque_outlined,
                                      size: 44,
                                      color: Theme.of(context).primaryColor,
                                    ),
                                  ),
                                  const SizedBox(height: 18),
                                  Text(
                                    "No mosques found within ${nearbyMosqueController.km.value.trim()}",
                                    style: robotoBold.copyWith(
                                      fontSize: Dimensions.FONT_SIZE_LARGE,
                                      color: Theme.of(context)
                                          .textTheme
                                          .bodyLarge
                                          ?.color,
                                    ),
                                    textAlign: TextAlign.center,
                                  ),
                                  const SizedBox(height: 8),
                                  Text(
                                    "Try increasing the search area to 3 KM or 5 KM from the dropdown above.",
                                    style: robotoRegular.copyWith(
                                      fontSize: Dimensions.FONT_SIZE_SMALL,
                                      color: Theme.of(context).disabledColor,
                                    ),
                                    textAlign: TextAlign.center,
                                  ),
                                  const SizedBox(height: 20),
                                  ElevatedButton.icon(
                                    style: ElevatedButton.styleFrom(
                                      backgroundColor:
                                          Theme.of(context).primaryColor,
                                      shape: RoundedRectangleBorder(
                                        borderRadius: BorderRadius.circular(
                                          Dimensions.RADIUS_DEFAULT,
                                        ),
                                      ),
                                      padding: const EdgeInsets.symmetric(
                                        horizontal: 22,
                                        vertical: 10,
                                      ),
                                    ),
                                    onPressed: () {
                                      nearbyMosqueController
                                          .searchNearbyPlaces();
                                    },
                                    icon: const Icon(
                                      Icons.refresh,
                                      color: Colors.white,
                                      size: 18,
                                    ),
                                    label: const Text(
                                      "Search Again",
                                      style: TextStyle(
                                        color: Colors.white,
                                        fontWeight: FontWeight.bold,
                                      ),
                                    ),
                                  ),
                                ],
                              ),
                            )
                          else
                            ListView.builder(
                              primary: false,
                              shrinkWrap: true,
                              physics: const NeverScrollableScrollPhysics(),
                              itemCount: nearbyMosqueController.places.length,
                              itemBuilder: (BuildContext context, int index) {
                                var place = nearbyMosqueController.places[index];
                                double lat = (place["geometry"]?["location"]
                                            ?["lat"] ??
                                        place["lat"] ??
                                        0.0)
                                    .toDouble();
                                double lng = (place["geometry"]?["location"]
                                            ?["lng"] ??
                                        place["lng"] ??
                                        0.0)
                                    .toDouble();
                                String distanceText =
                                    place["distance_text"]?.toString() ?? "";

                                return GestureDetector(
                                  onTap: () async {
                                    final url =
                                        'https://www.google.com/maps/dir/?api=1&destination=$lat,$lng';
                                    try {
                                      final uri = Uri.parse(url);
                                      if (await canLaunchUrl(uri)) {
                                        await launchUrl(
                                          uri,
                                          mode: LaunchMode.externalApplication,
                                        );
                                      } else {
                                        await launch(url);
                                      }
                                    } catch (_) {
                                      showCustomSnackBar(
                                        "please_try_again".tr,
                                        isError: true,
                                      );
                                    }
                                  },
                                  child: Card(
                                    elevation: 1.5,
                                    margin: const EdgeInsets.only(bottom: 10),
                                    shape: RoundedRectangleBorder(
                                      borderRadius: BorderRadius.circular(
                                        Dimensions.RADIUS_DEFAULT,
                                      ),
                                    ),
                                    clipBehavior: Clip.antiAlias,
                                    color: Theme.of(context).cardColor,
                                    shadowColor: Get.isDarkMode
                                        ? Colors.grey[800]!
                                        : Colors.grey[200]!,
                                    child: ListTile(
                                      contentPadding:
                                          const EdgeInsetsDirectional.symmetric(
                                        horizontal:
                                            Dimensions.PADDING_SIZE_DEFAULT,
                                        vertical: 4,
                                      ),
                                      leading: Container(
                                        width: 44,
                                        height: 44,
                                        decoration: BoxDecoration(
                                          shape: BoxShape.circle,
                                          color: Theme.of(context)
                                              .primaryColor
                                              .withOpacity(0.12),
                                        ),
                                        child: Icon(
                                          Icons.mosque,
                                          color: Theme.of(context).primaryColor,
                                          size: 24,
                                        ),
                                      ),
                                      title: Text(
                                        place['name'] ?? "--",
                                        style: robotoMedium.copyWith(
                                          fontSize: Dimensions.FONT_SIZE_LARGE,
                                        ),
                                        maxLines: 1,
                                        overflow: TextOverflow.ellipsis,
                                      ),
                                      subtitle: Column(
                                        crossAxisAlignment:
                                            CrossAxisAlignment.start,
                                        children: [
                                          const SizedBox(height: 3),
                                          Text(
                                            place['vicinity'] ?? "--",
                                            style: robotoRegular.copyWith(
                                              fontSize:
                                                  Dimensions.FONT_SIZE_SMALL,
                                              color: Theme.of(context)
                                                  .disabledColor,
                                            ),
                                            maxLines: 2,
                                            overflow: TextOverflow.ellipsis,
                                          ),
                                          if (distanceText.isNotEmpty) ...[
                                            const SizedBox(height: 4),
                                            Row(
                                              children: [
                                                Icon(
                                                  Icons.navigation,
                                                  size: 11,
                                                  color: Theme.of(context)
                                                      .primaryColor,
                                                ),
                                                const SizedBox(width: 4),
                                                Text(
                                                  distanceText,
                                                  style: robotoBold.copyWith(
                                                    fontSize: Dimensions
                                                        .FONT_SIZE_EXTRA_SMALL,
                                                    color: Theme.of(context)
                                                        .primaryColor,
                                                  ),
                                                ),
                                              ],
                                            ),
                                          ],
                                        ],
                                      ),
                                      trailing: SvgPicture.asset(
                                        Images.Icon_Right_Arrow,
                                        color: Theme.of(context).primaryColor,
                                        height: 24,
                                      ),
                                    ),
                                  ),
                                );
                              },
                            ),
                        ],
                      ),
                    ),
            );
          },
        ),
      ),
    );
  }
}
